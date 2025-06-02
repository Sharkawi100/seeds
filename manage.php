<?php
/**
 * جذور Project Management Dashboard
 * Version: 2.0
 * Security: Localhost only
 */

// Security check - only allow localhost
if (
    !in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']) &&
    !in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', '::1'])
) {
    http_response_code(403);
    die('<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .error-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 { color: #dc3545; margin-bottom: 10px; }
        p { color: #666; }
    </style>
</head>
<body>
    <div class="error-box">
        <h1>⚠️ تم رفض الوصول</h1>
        <p>هذه الصفحة متاحة فقط من localhost</p>
    </div>
</body>
</html>');
}

// Configuration
define('PROJECT_PATH', 'C:\\xampp\\htdocs\\juzoor-quiz');
define('MYSQL_BIN', 'C:\\xampp\\mysql\\bin');
define('WINSCP_PATH', 'C:\\Program Files (x86)\\WinSCP\\WinSCP.com');

// For Git, we'll just use 'git' command since it should be in PATH
define('GIT_PATH', 'git');

// Initialize Git config for safety
@exec('git config --global --add safe.directory ' . escapeshellarg(PROJECT_PATH) . ' 2>&1');

// Set environment variables for Git
putenv('GIT_TERMINAL_PROMPT=0');
putenv('GIT_SSH_COMMAND=ssh -o BatchMode=yes');

/**
 * Class: CommandExecutor
 * Handles command execution with proper error handling and output capture
 */
class CommandExecutor
{
    private $workingDir;
    private $env;

    public function __construct($workingDir = null)
    {
        $this->workingDir = $workingDir ?: PROJECT_PATH;

        // Set up environment with proper PATH for Git
        $this->env = $_ENV;
        $this->env['PATH'] = getenv('PATH');

        // Add common Git paths to PATH if not already there
        $additionalPaths = [
            'C:\\Program Files\\Git\\cmd',
            'C:\\Program Files\\Git\\bin',
            'C:\\Program Files (x86)\\Git\\cmd',
            'C:\\Program Files (x86)\\Git\\bin',
            'C:\\Git\\cmd',
            'C:\\Git\\bin'
        ];

        foreach ($additionalPaths as $path) {
            if (file_exists($path) && strpos($this->env['PATH'], $path) === false) {
                $this->env['PATH'] .= ';' . $path;
            }
        }
    }

    /**
     * Execute a command and return structured result
     */
    public function execute($command, $input = null)
    {
        // For Git commands, always use simple 'git' from PATH
        if (strpos($command, 'git') !== false || strpos($command, 'Git') !== false) {
            // Replace any full Git path with just 'git'
            $patterns = [
                '/^"?C:\\\\Program Files\\\\Git\\\\cmd\\\\git\.exe"?\s+/' => 'git ',
                '/^"?C:\\\\Program Files\\\\Git\\\\bin\\\\git\.exe"?\s+/' => 'git ',
                '/^"?C:\\\\Program Files \(x86\)\\\\Git\\\\cmd\\\\git\.exe"?\s+/' => 'git ',
                '/^"?C:\\\\Program Files \(x86\)\\\\Git\\\\bin\\\\git\.exe"?\s+/' => 'git ',
                '/^C:\\\\Program\s+Files\\\\Git\\\\cmd\\\\git\.exe\s+/' => 'git ',
                '/^C:\\\\Program\s+Files\\\\Git\\\\bin\\\\git\.exe\s+/' => 'git ',
            ];

            foreach ($patterns as $pattern => $replacement) {
                $command = preg_replace($pattern, $replacement, $command);
            }

            // Now execute using shell_exec in project directory
            $originalDir = getcwd();
            chdir($this->workingDir);

            $output = @shell_exec($command . ' 2>&1');
            chdir($originalDir);

            if ($output !== null) {
                return [
                    'success' => strpos($output, 'fatal:') === false &&
                        strpos($output, 'error:') === false &&
                        strpos($output, 'is not recognized') === false,
                    'output' => $output,
                    'error' => '',
                    'exitCode' => 0,
                    'command' => $command,
                    'method' => 'shell_exec_git'
                ];
            }
        }

        // For other commands, use the existing logic
        if (stripos(PHP_OS, 'WIN') === 0) {
            if (substr($command, 0, 1) === '"') {
                $fullCommand = 'cmd /c ' . $command;
            } else {
                $fullCommand = 'cmd /c "' . str_replace('"', '""', $command) . '"';
            }
        } else {
            $fullCommand = $command;
        }

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];

        $process = @proc_open(
            $fullCommand,
            $descriptors,
            $pipes,
            $this->workingDir,
            $this->env
        );

        if (!is_resource($process)) {
            $fallbackOutput = @shell_exec($command . ' 2>&1');
            return [
                'success' => !empty($fallbackOutput) && strpos($fallbackOutput, 'fatal') === false,
                'output' => $fallbackOutput ?: '',
                'error' => '',
                'exitCode' => empty($fallbackOutput) ? 1 : 0,
                'command' => $command,
                'method' => 'shell_exec'
            ];
        }

        stream_set_blocking($pipes[0], false);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        if ($input !== null) {
            fwrite($pipes[0], $input);
        }
        fclose($pipes[0]);

        $output = '';
        $error = '';
        $timeout = 30;
        $startTime = time();

        while (!feof($pipes[1]) || !feof($pipes[2])) {
            if ((time() - $startTime) > $timeout) {
                proc_terminate($process);
                break;
            }

            $output .= fread($pipes[1], 8192);
            $error .= fread($pipes[2], 8192);
            usleep(10000);
        }

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        return [
            'success' => $exitCode === 0,
            'output' => $output,
            'error' => $error,
            'exitCode' => $exitCode,
            'command' => $command,
            'method' => 'proc_open'
        ];
    }

    /**
     * Execute multiple commands in sequence
     */
    public function executeSequence(array $commands)
    {
        $results = [];
        $allSuccess = true;

        foreach ($commands as $command) {
            $result = $this->execute($command);
            $results[] = $result;

            if (!$result['success']) {
                $allSuccess = false;
                break;
            }
        }

        return [
            'success' => $allSuccess,
            'results' => $results
        ];
    }
}

/**
 * Class: GitManager
 * Handles Git operations
 */
class GitManager
{
    private $executor;

    public function __construct()
    {
        $this->executor = new CommandExecutor(PROJECT_PATH);
    }

    public function isGitAvailable()
    {
        // Method 1: Try simple git command (what works in batch files)
        $shellResult = @shell_exec('git --version 2>&1');
        if ($shellResult && strpos($shellResult, 'git version') !== false) {
            return true;
        }

        // Method 2: Try with CD to project directory
        $originalDir = getcwd();
        chdir(PROJECT_PATH);
        $result = @shell_exec('git --version 2>&1');
        chdir($originalDir);

        if ($result && strpos($result, 'git version') !== false) {
            return true;
        }

        return false;
    }

    public function getGitVersion()
    {
        // Try simple git command first (this is what works in batch files)
        $shellResult = @shell_exec('git --version 2>&1');
        if ($shellResult && strpos($shellResult, 'git version') !== false) {
            return [
                'version' => trim($shellResult),
                'method' => 'shell_exec',
                'command' => 'git --version'
            ];
        }

        // Try from project directory
        $originalDir = getcwd();
        chdir(PROJECT_PATH);
        $result = @shell_exec('git --version 2>&1');
        chdir($originalDir);

        if ($result && strpos($result, 'git version') !== false) {
            return [
                'version' => trim($result),
                'method' => 'shell_exec (from project dir)',
                'command' => 'git --version'
            ];
        }

        return null;
    }

    public function isGitRepository()
    {
        // Check for .git directory first
        if (is_dir(PROJECT_PATH . '\\.git')) {
            return true;
        }

        // Try git command
        $originalDir = getcwd();
        chdir(PROJECT_PATH);

        $result = @shell_exec('git rev-parse --git-dir 2>&1');
        chdir($originalDir);

        if ($result && strpos($result, '.git') !== false && strpos($result, 'fatal') === false) {
            return true;
        }

        return false;
    }

    public function getBranch()
    {
        // Check if Git is available
        if (!$this->isGitAvailable()) {
            return 'Git غير مثبت';
        }

        // Check if this is a Git repository
        if (!$this->isGitRepository()) {
            return 'ليس مستودع Git';
        }

        $result = $this->executor->execute(GIT_PATH . ' branch --show-current');
        if ($result['success'] && !empty(trim($result['output']))) {
            return trim($result['output']);
        }

        // Try alternative method for older Git versions
        $result = $this->executor->execute(GIT_PATH . ' rev-parse --abbrev-ref HEAD');
        if ($result['success']) {
            return trim($result['output']);
        }

        return 'غير متاح';
    }

    public function getStatus()
    {
        // Check if Git is available
        if (!$this->isGitAvailable()) {
            return ['status' => 'Git غير مثبت', 'hasChanges' => false, 'details' => ''];
        }

        // Check if this is a Git repository
        if (!$this->isGitRepository()) {
            return ['status' => 'ليس مستودع Git', 'hasChanges' => false, 'details' => ''];
        }

        // Try to get status
        $originalDir = getcwd();
        chdir(PROJECT_PATH);

        $output = @shell_exec('git status --porcelain 2>&1');
        chdir($originalDir);

        if ($output === null || strpos($output, 'fatal') !== false) {
            return [
                'status' => 'خطأ في Git',
                'hasChanges' => false,
                'details' => $output ?: 'Unable to get status'
            ];
        }

        $output = trim($output);
        return [
            'status' => empty($output) ? 'نظيف' : 'يوجد تغييرات',
            'hasChanges' => !empty($output),
            'changes' => $output,
            'details' => ''
        ];
    }

    public function getRemoteUrl()
    {
        $originalDir = getcwd();
        chdir(PROJECT_PATH);

        $result = @shell_exec('git config --get remote.origin.url 2>&1');
        chdir($originalDir);

        if ($result && !empty(trim($result)) && strpos($result, 'fatal') === false) {
            $url = trim($result);
            // Convert SSH URL to HTTPS for browser
            if (strpos($url, 'git@github.com:') === 0) {
                $url = str_replace('git@github.com:', 'https://github.com/', $url);
                $url = str_replace('.git', '', $url);
            }
            return $url;
        }
        return '#';
    }

    // Add these methods to the GitManager class (place them before pushToGitHub method)

    /**
     * Check if Git user configuration is set
     * @return array Configuration status and values
     */
    public function checkGitConfig()
    {
        // Check if user.name and user.email are set
        $originalDir = getcwd();
        chdir(PROJECT_PATH);

        $userName = @shell_exec('git config --global user.name 2>&1');
        $userEmail = @shell_exec('git config --global user.email 2>&1');

        chdir($originalDir);

        return [
            'hasName' => !empty(trim($userName)) && strpos($userName, 'fatal') === false,
            'hasEmail' => !empty(trim($userEmail)) && strpos($userEmail, 'fatal') === false,
            'name' => trim($userName ?: ''),
            'email' => trim($userEmail ?: '')
        ];
    }

    /**
     * Set Git user configuration
     * @param string|null $name User name
     * @param string|null $email User email
     * @return array Results of setting configuration
     */
    public function setGitConfig($name = null, $email = null)
    {
        $originalDir = getcwd();
        chdir(PROJECT_PATH);

        $results = [];

        if ($name) {
            $cmd = 'git config --global user.name "' . str_replace('"', '\\"', $name) . '"';
            $result = @shell_exec($cmd . ' 2>&1');
            $results['name'] = empty($result) || strpos($result, 'fatal') === false;
        }

        if ($email) {
            $cmd = 'git config --global user.email "' . str_replace('"', '\\"', $email) . '"';
            $result = @shell_exec($cmd . ' 2>&1');
            $results['email'] = empty($result) || strpos($result, 'fatal') === false;
        }

        chdir($originalDir);

        return $results;
    }

    public function pushToGitHub($commitMessage = null)
    {
        // Check Git availability first
        if (!$this->isGitAvailable()) {
            return [
                'success' => false,
                'message' => 'Git غير مثبت على النظام',
                'output' => 'يرجى تثبيت Git من https://git-scm.com/'
            ];
        }

        // Check if this is a Git repository
        if (!$this->isGitRepository()) {
            return [
                'success' => false,
                'message' => 'المجلد ليس مستودع Git',
                'output' => 'يرجى تهيئة Git في المشروع أولاً'
            ];
        }

        // Check if there are changes
        $status = $this->getStatus();
        if (!$status['hasChanges']) {
            return [
                'success' => false,
                'message' => 'لا توجد تغييرات للرفع',
                'output' => 'Working directory is clean'
            ];
        }

        // Generate commit message if not provided
        if (empty($commitMessage)) {
            $commitMessage = 'Update from ' . date('Y-m-d H:i:s');
        }

        // Execute git commands sequence
        $commands = [
            GIT_PATH . ' add -A',
            GIT_PATH . ' commit -m ' . escapeshellarg($commitMessage),
            GIT_PATH . ' push origin main'
        ];

        $result = $this->executor->executeSequence($commands);

        // Compile output
        $fullOutput = "Git Executable: " . GIT_PATH . "\n";
        $fullOutput .= "Working Directory: " . PROJECT_PATH . "\n";
        $fullOutput .= str_repeat('=', 50) . "\n\n";

        foreach ($result['results'] as $idx => $cmdResult) {
            $fullOutput .= "Command: {$cmdResult['command']}\n";
            $fullOutput .= "Exit Code: {$cmdResult['exitCode']}\n";
            if (!empty($cmdResult['output'])) {
                $fullOutput .= "Output: " . $cmdResult['output'] . "\n";
            }
            if (!empty($cmdResult['error'])) {
                $fullOutput .= "Error: " . $cmdResult['error'] . "\n";
            }
            $fullOutput .= str_repeat('-', 50) . "\n";
        }

        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'تم رفع التغييرات بنجاح' : 'فشل رفع التغييرات',
            'output' => $fullOutput
        ];
    }
}

/**
 * Class: ProjectManager
 * Main project management class
 */
class ProjectManager
{
    private $executor;
    private $git;

    public function __construct()
    {
        $this->executor = new CommandExecutor();
        $this->git = new GitManager();
    }

    public function getProjectInfo()
    {
        $gitStatus = $this->git->getStatus();
        $gitConfig = $this->git->checkGitConfig();
        $gitInfo = [
            'available' => $this->git->isGitAvailable(),
            'isRepo' => $this->git->isGitRepository(),
            'hasConfig' => $gitConfig['hasName'] && $gitConfig['hasEmail'],
            'userName' => $gitConfig['name'] ?? '',
            'userEmail' => $gitConfig['email'] ?? ''
        ];

        return [
            'environment' => 'محلي (localhost)',
            'projectPath' => PROJECT_PATH,
            'phpVersion' => phpversion(),
            'gitBranch' => $this->git->getBranch(),
            'gitStatus' => $gitStatus['status'],
            'gitStatusDetails' => $gitStatus['details'] ?? '',
            'gitRemote' => $this->git->getRemoteUrl(),
            'gitInfo' => $gitInfo,
            'serverTime' => date('Y-m-d H:i:s')
        ];
    }

    public function syncFromLive()
    {
        $batFile = PROJECT_PATH . '\\sync-from-live.bat';
        if (!file_exists($batFile)) {
            return [
                'success' => false,
                'message' => 'ملف sync-from-live.bat غير موجود',
                'output' => 'File not found: ' . $batFile
            ];
        }

        // For batch files, use "call" to ensure proper execution
        $result = $this->executor->execute('call ' . escapeshellarg($batFile));
        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'تمت المزامنة بنجاح' : 'فشلت المزامنة',
            'output' => $result['output'] . "\n" . $result['error']
        ];
    }

    public function backupDatabase()
    {
        $batFile = PROJECT_PATH . '\\backup-database.bat';
        if (!file_exists($batFile)) {
            return [
                'success' => false,
                'message' => 'ملف backup-database.bat غير موجود',
                'output' => 'File not found: ' . $batFile
            ];
        }

        $result = $this->executor->execute('call ' . escapeshellarg($batFile));
        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'تم النسخ الاحتياطي بنجاح' : 'فشل النسخ الاحتياطي',
            'output' => $result['output'] . "\n" . $result['error']
        ];
    }

    public function clearCache()
    {
        // Change to project directory
        $originalDir = getcwd();
        chdir(PROJECT_PATH);

        $phpPath = PHP_BINARY ?: 'php';
        $artisanPath = 'artisan';

        if (!file_exists($artisanPath)) {
            chdir($originalDir);
            return [
                'success' => false,
                'message' => 'ملف artisan غير موجود',
                'output' => 'Laravel artisan file not found at: ' . PROJECT_PATH . '\\artisan'
            ];
        }

        $output = "تنفيذ أوامر مسح الذاكرة المؤقتة:\n";
        $output .= "PHP Path: " . $phpPath . "\n";
        $output .= "Working Directory: " . PROJECT_PATH . "\n";
        $output .= str_repeat('=', 50) . "\n\n";

        $commands = [
            'cache:clear' => 'php artisan cache:clear',
            'config:clear' => 'php artisan config:clear',
            'view:clear' => 'php artisan view:clear',
            'route:clear' => 'php artisan route:clear'
        ];

        $allSuccess = true;
        foreach ($commands as $name => $cmd) {
            $output .= "Running: " . $cmd . "\n";
            $result = @shell_exec($cmd . ' 2>&1');

            if ($result !== null) {
                $output .= $result;
                if (strpos($result, 'ERROR') !== false || strpos($result, 'Exception') !== false) {
                    $allSuccess = false;
                }
            } else {
                $output .= "Failed to execute command\n";
                $allSuccess = false;
            }
            $output .= "\n" . str_repeat('-', 40) . "\n";
        }

        chdir($originalDir);

        return [
            'success' => $allSuccess,
            'message' => $allSuccess ? 'تم مسح جميع الذاكرة المؤقتة' : 'حدث خطأ أثناء مسح الذاكرة المؤقتة',
            'output' => $output
        ];
    }

    public function npmInstall()
    {
        // Change to project directory
        $originalDir = getcwd();
        chdir(PROJECT_PATH);

        // Check if npm is available
        $npmCheck = @shell_exec('npm --version 2>&1');
        if (!$npmCheck || strpos($npmCheck, 'not recognized') !== false) {
            chdir($originalDir);
            return [
                'success' => false,
                'message' => 'npm غير مثبت على النظام',
                'output' => 'Please install Node.js from https://nodejs.org/'
            ];
        }

        $output = "Running npm install...\n";
        $output .= "NPM Version: " . trim($npmCheck) . "\n";
        $output .= "Working Directory: " . PROJECT_PATH . "\n";
        $output .= str_repeat('=', 50) . "\n\n";

        $result = @shell_exec('npm install 2>&1');
        chdir($originalDir);

        $success = $result !== null && strpos($result, 'npm ERR!') === false;

        return [
            'success' => $success,
            'message' => $success ? 'تم تثبيت الحزم بنجاح' : 'فشل تثبيت الحزم',
            'output' => $output . $result
        ];
    }

    public function npmBuild()
    {
        // Change to project directory
        $originalDir = getcwd();
        chdir(PROJECT_PATH);

        // Check if npm is available
        $npmCheck = @shell_exec('npm --version 2>&1');
        if (!$npmCheck || strpos($npmCheck, 'not recognized') !== false) {
            chdir($originalDir);
            return [
                'success' => false,
                'message' => 'npm غير مثبت على النظام',
                'output' => 'Please install Node.js from https://nodejs.org/'
            ];
        }

        $output = "Running npm build...\n";
        $output .= "NPM Version: " . trim($npmCheck) . "\n";
        $output .= "Working Directory: " . PROJECT_PATH . "\n";
        $output .= str_repeat('=', 50) . "\n\n";

        $result = @shell_exec('npm run build 2>&1');
        chdir($originalDir);

        $success = $result !== null && strpos($result, 'npm ERR!') === false;

        return [
            'success' => $success,
            'message' => $success ? 'تم بناء الملفات بنجاح' : 'فشل بناء الملفات',
            'output' => $output . $result
        ];
    }

    public function deployToLive()
    {
        $batFile = PROJECT_PATH . '\\deploy-to-live.bat';
        if (!file_exists($batFile)) {
            return [
                'success' => false,
                'message' => 'ملف deploy-to-live.bat غير موجود',
                'output' => 'File not found: ' . $batFile
            ];
        }

        // Execute with confirmation using echo to pipe "yes"
        $result = $this->executor->execute('echo yes | call ' . escapeshellarg($batFile));
        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'تم النشر بنجاح على الخادم المباشر' : 'فشل النشر',
            'output' => $result['output'] . "\n" . $result['error']
        ];
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json; charset=utf-8');

    $manager = new ProjectManager();
    $response = ['success' => false, 'message' => 'Invalid action', 'output' => ''];

    try {
        switch ($_POST['action'] ?? '') {
            case 'sync_from_live':
                $response = $manager->syncFromLive();
                break;

            case 'push_to_github':
                $git = new GitManager();
                $commitMessage = $_POST['commit_message'] ?? null;
                $response = $git->pushToGitHub($commitMessage);
                break;

            case 'backup_database':
                $response = $manager->backupDatabase();
                break;

            case 'clear_cache':
                $response = $manager->clearCache();
                break;

            case 'npm_install':
                $response = $manager->npmInstall();
                break;

            case 'npm_build':
                $response = $manager->npmBuild();
                break;

            case 'deploy_to_live':
                if ($_POST['confirm'] !== 'true') {
                    $response = [
                        'success' => false,
                        'message' => 'يجب تأكيد عملية النشر',
                        'output' => ''
                    ];
                } else {
                    $response = $manager->deployToLive();
                }
                break;

            case 'get_project_info':
                $response = [
                    'success' => true,
                    'data' => $manager->getProjectInfo()
                ];
                break;

            case 'set_git_config':
                $git = new GitManager();
                $name = $_POST['git_name'] ?? '';
                $email = $_POST['git_email'] ?? '';

                if (empty($name) || empty($email)) {
                    $response = [
                        'success' => false,
                        'message' => 'الاسم والبريد الإلكتروني مطلوبان'
                    ];
                } else {
                    $result = $git->setGitConfig($name, $email);
                    $response = [
                        'success' => true,
                        'message' => 'تم تكوين Git بنجاح',
                        'output' => "Git configured:\nName: $name\nEmail: $email"
                    ];
                }
                break;

            case 'check_git_config':
                $git = new GitManager();
                $config = $git->checkGitConfig();
                $response = [
                    'success' => true,
                    'data' => $config
                ];
                break;
        }
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage(),
            'output' => $e->getTraceAsString()
        ];
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Initialize for page load
$manager = new ProjectManager();
$projectInfo = $manager->getProjectInfo();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة مشروع جُذور - الإصدار 2.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --secondary: #764ba2;
            --success: #48bb78;
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
            --dark: #2d3748;
            --light: #f7fafc;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header Styles */
        .header {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            animation: slideDown 0.5s ease-out;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .header .subtitle {
            text-align: center;
            color: #718096;
            font-size: 1.1rem;
        }

        /* Status Card */
        .status-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease-out 0.2s both;
        }

        .status-card h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .status-item {
            background: var(--light);
            padding: 1rem;
            border-radius: 0.5rem;
            border-right: 4px solid var(--primary);
            transition: transform 0.2s;
        }

        .status-item:hover {
            transform: translateX(-5px);
        }

        .status-item strong {
            display: block;
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .status-item span {
            color: #4a5568;
            font-size: 0.95rem;
        }

        /* Actions Grid */
        .actions-section {
            margin-bottom: 2rem;
        }

        .actions-section h2 {
            color: white;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .action-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: scaleIn 0.5s ease-out;
            animation-fill-mode: both;
        }

        .action-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .action-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .action-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .action-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .action-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .action-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .action-card:nth-child(7) {
            animation-delay: 0.7s;
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent, rgba(102, 126, 234, 0.1));
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .action-card:hover::before {
            transform: translateX(0);
        }

        .action-card.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .action-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            animation: bounce 2s infinite;
        }

        .action-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .action-desc {
            color: #718096;
            font-size: 0.95rem;
        }

        .deploy-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .deploy-card .action-title,
        .deploy-card .action-desc {
            color: white;
        }

        .deploy-card::before {
            background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.2));
        }

        /* Links Section */
        .links-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease-out 0.8s both;
        }

        .links-card h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--dark);
        }

        .links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .link-item {
            background: var(--light);
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .link-item:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: var(--shadow);
        }

        .link-item .icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .link-item .label {
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            animation: fadeIn 0.3s;
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            margin: 5vh auto;
            position: relative;
            display: flex;
            flex-direction: column;
            animation: slideUp 0.3s;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light);
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-close {
            background: var(--light);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: #e2e8f0;
            transform: rotate(90deg);
        }

        .modal-body {
            flex: 1;
            overflow: auto;
            background: #1a202c;
            color: #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.5rem;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.9rem;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* Loading Spinner */
        .spinner {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
        }

        .spinner::after {
            content: '';
            display: block;
            width: 48px;
            height: 48px;
            margin: 6px;
            border-radius: 50%;
            border: 6px solid var(--primary);
            border-color: var(--primary) transparent var(--primary) transparent;
            animation: spin 1.2s linear infinite;
        }

        /* Animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge-success {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge-warning {
            background: #feebc8;
            color: #7b341e;
        }

        .badge-info {
            background: #bee3f8;
            color: #2a4365;
        }

        /* Toast notifications */
        .toast {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-lg);
            display: none;
            animation: slideUp 0.3s;
            z-index: 2000;
        }

        .toast.success {
            border-right: 4px solid var(--success);
        }

        .toast.error {
            border-right: 4px solid var(--danger);
        }

        /* Input styles for modals */
        input[type="text"],
        input[type="email"] {
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        button:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .links-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .modal-content {
                width: 95%;
                margin: 2.5vh auto;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <span>🌱</span>
                <span>إدارة مشروع جُذور</span>
            </h1>
            <p class="subtitle">لوحة تحكم احترافية لإدارة التطوير والنشر - الإصدار 2.0</p>
        </div>

        <!-- Status Card -->
        <div class="status-card">
            <h2>📊 حالة المشروع</h2>
            <div class="status-grid">
                <div class="status-item">
                    <strong>البيئة الحالية</strong>
                    <span><?php echo $projectInfo['environment']; ?></span>
                </div>
                <div class="status-item">
                    <strong>مسار المشروع</strong>
                    <span style="font-size: 0.85rem;"><?php echo $projectInfo['projectPath']; ?></span>
                </div>
                <div class="status-item">
                    <strong>إصدار PHP</strong>
                    <span><?php echo $projectInfo['phpVersion']; ?></span>
                </div>
                <div class="status-item">
                    <strong>فرع Git</strong>
                    <span class="badge badge-info"><?php echo $projectInfo['gitBranch']; ?></span>
                </div>
                <div class="status-item">
                    <strong>حالة Git</strong>
                    <span
                        class="badge <?php echo $projectInfo['gitStatus'] === 'نظيف' ? 'badge-success' : 'badge-warning'; ?>">
                        <?php echo $projectInfo['gitStatus']; ?>
                    </span>
                </div>
                <div class="status-item">
                    <strong>مستخدم Git</strong>
                    <span style="font-size: 0.85rem;">
                        <?php
                        if ($projectInfo['gitInfo']['hasConfig']) {
                            echo htmlspecialchars($projectInfo['gitInfo']['userName']);
                        } else {
                            echo '<span style="color: #e53e3e;">غير مكوّن</span> <a href="#" onclick="showGitConfigModal(); return false;" style="color: var(--primary);">تكوين</a>';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions Section -->
        <div class="actions-section">
            <h2>🛠️ الإجراءات المتاحة</h2>
            <div class="actions-grid">
                <div class="action-card" onclick="executeAction('sync_from_live')">
                    <div class="spinner"></div>
                    <span class="action-icon">⬇️</span>
                    <h3 class="action-title">مزامنة من المباشر</h3>
                    <p class="action-desc">جلب آخر التحديثات من الخادم المباشر إلى البيئة المحلية</p>
                </div>

                <div class="action-card" onclick="executeAction('backup_database')">
                    <div class="spinner"></div>
                    <span class="action-icon">💾</span>
                    <h3 class="action-title">نسخة احتياطية</h3>
                    <p class="action-desc">حفظ نسخة احتياطية من قاعدة البيانات مع الطابع الزمني</p>
                </div>

                <div class="action-card" onclick="showGitDialog()">
                    <div class="spinner"></div>
                    <span class="action-icon">📤</span>
                    <h3 class="action-title">رفع إلى GitHub</h3>
                    <p class="action-desc">حفظ التغييرات في مستودع GitHub مع رسالة commit</p>
                </div>

                <div class="action-card" onclick="executeAction('clear_cache')">
                    <div class="spinner"></div>
                    <span class="action-icon">🗑️</span>
                    <h3 class="action-title">مسح الذاكرة المؤقتة</h3>
                    <p class="action-desc">مسح جميع ملفات Laravel المؤقتة</p>
                </div>

                <div class="action-card" onclick="executeAction('npm_install')">
                    <div class="spinner"></div>
                    <span class="action-icon">📦</span>
                    <h3 class="action-title">تثبيت الحزم</h3>
                    <p class="action-desc">تشغيل npm install لتثبيت التبعيات</p>
                </div>

                <div class="action-card" onclick="executeAction('npm_build')">
                    <div class="spinner"></div>
                    <span class="action-icon">🏗️</span>
                    <h3 class="action-title">بناء الملفات</h3>
                    <p class="action-desc">تشغيل npm run build لبناء assets الإنتاج</p>
                </div>

                <div class="action-card deploy-card" onclick="confirmDeploy()">
                    <div class="spinner"></div>
                    <span class="action-icon">🚀</span>
                    <h3 class="action-title">نشر على الخادم المباشر</h3>
                    <p class="action-desc">⚠️ تأكد من اختبار جميع التغييرات قبل النشر!</p>
                </div>
            </div>
        </div>

        <!-- Links Card -->
        <div class="links-card">
            <h2>🔗 روابط سريعة</h2>
            <div class="links-grid">
                <a href="http://localhost/juzoor-quiz" target="_blank" class="link-item">
                    <span class="icon">🏠</span>
                    <span class="label">الموقع المحلي</span>
                </a>
                <a href="https://iseraj.com/roots" target="_blank" class="link-item">
                    <span class="icon">🌐</span>
                    <span class="label">الموقع المباشر</span>
                </a>
                <a href="http://localhost/phpmyadmin" target="_blank" class="link-item">
                    <span class="icon">🗄️</span>
                    <span class="label">phpMyAdmin</span>
                </a>
                <a href="<?php echo $projectInfo['gitRemote']; ?>" target="_blank" class="link-item">
                    <span class="icon">📚</span>
                    <span class="label">GitHub</span>
                </a>
            </div>
        </div>

        <!-- Debug Info (hidden by default) -->
        <?php if (isset($_GET['debug'])): ?>
            <div class="status-card" style="margin-top: 2rem; background: #1a202c; color: #e2e8f0;">
                <h2 style="color: #e2e8f0;">🐛 معلومات التشخيص</h2>
                <pre style="font-family: monospace; font-size: 0.9rem; line-height: 1.5;">
    Git Configuration:
    - Defined Path: <?php echo GIT_PATH; ?>

    - File Exists: <?php echo (GIT_PATH !== 'git' && file_exists(GIT_PATH)) ? 'Yes ✓' : 'No ✗'; ?>

    - Git Available: <?php echo $projectInfo['gitInfo']['available'] ? 'Yes ✓' : 'No ✗'; ?>

    - Is Git Repository: <?php echo $projectInfo['gitInfo']['isRepo'] ? 'Yes ✓' : 'No ✗'; ?>

    <?php
    $gitManager = new GitManager();
    $gitVersion = $gitManager->getGitVersion();
    if ($gitVersion) {
        echo "- Git Version: " . $gitVersion['version'] . "\n";
        echo "- Detection Method: " . $gitVersion['method'] . "\n";
        echo "- Command Used: " . $gitVersion['command'] . "\n";
    } else {
        echo "- Git Version: Not detected\n";
    }
    ?>

    Git Paths Checked:
    <?php
    $checkedPaths = [
        'C:\\Program Files\\Git\\cmd\\git.exe',
        'C:\\Program Files\\Git\\bin\\git.exe',
        'C:\\Program Files (x86)\\Git\\cmd\\git.exe',
        'C:\\Program Files (x86)\\Git\\bin\\git.exe',
        'C:\\Git\\cmd\\git.exe',
        'C:\\Git\\bin\\git.exe',
        'C:\\Users\\' . get_current_user() . '\\AppData\\Local\\Programs\\Git\\cmd\\git.exe'
    ];

    foreach ($checkedPaths as $path) {
        echo sprintf("  %-60s %s\n", $path, file_exists($path) ? '✓ Found' : '✗ Not found');
    }
    ?>

    Shell Commands Test:
    <?php
    $testCommands = [
        'where git' => @shell_exec('where git 2>&1'),
        'git --version' => @shell_exec('git --version 2>&1'),
        'echo %PATH%' => substr(@shell_exec('echo %PATH% 2>&1'), 0, 200) . '...'
    ];

    foreach ($testCommands as $cmd => $output) {
        echo "\n{$cmd}:\n";
        echo $output ? trim($output) : '(no output)';
        echo "\n";
    }
    ?>

    Environment:
    - PHP Version: <?php echo phpversion(); ?>
    - PHP SAPI: <?php echo php_sapi_name(); ?>
    - OS: <?php echo PHP_OS; ?>
    - User: <?php echo get_current_user(); ?>
    - Working Dir: <?php echo getcwd(); ?>
    - Safe Mode: <?php echo ini_get('safe_mode') ? 'On' : 'Off'; ?>
    - Exec Enabled: <?php echo function_exists('exec') ? 'Yes' : 'No'; ?>
    - Shell_exec Enabled: <?php echo function_exists('shell_exec') ? 'Yes' : 'No'; ?>
    - Proc_open Enabled: <?php echo function_exists('proc_open') ? 'Yes' : 'No'; ?>
                </pre>
            </div>
        <?php endif; ?>
    </div>

    <!-- Output Modal -->
    <div id="outputModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">
                    <span id="modalIcon">⚙️</span>
                    <span id="modalTitleText">نتيجة العملية</span>
                </h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                جاري التنفيذ...
            </div>
        </div>
    </div>

    <!-- Git Config Modal -->
    <div id="gitConfigModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title">
                    <span>⚙️</span>
                    <span>تكوين Git</span>
                </h3>
                <button class="modal-close" onclick="closeGitConfigModal()">&times;</button>
            </div>
            <div style="padding: 2rem;">
                <p style="margin-bottom: 1.5rem; color: #4a5568;">
                    يحتاج Git إلى معرفة هويتك لتسجيل التغييرات. يرجى إدخال اسمك وبريدك الإلكتروني:
                </p>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">الاسم:</label>
                    <input type="text" id="gitName" placeholder="مثال: أحمد محمد"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 1rem;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">البريد الإلكتروني:</label>
                    <input type="email" id="gitEmail" placeholder="مثال: your-email@example.com"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 1rem;">
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button onclick="closeGitConfigModal()"
                        style="padding: 0.75rem 1.5rem; background: #e2e8f0; border: none; border-radius: 0.5rem; cursor: pointer; font-family: inherit; font-size: 1rem; transition: all 0.3s;">
                        إلغاء
                    </button>
                    <button id="saveGitConfigBtn" onclick="saveGitConfig()"
                        style="padding: 0.75rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-family: inherit; font-size: 1rem; transition: all 0.3s;">
                        حفظ التكوين
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <!-- Git Setup Help -->
    <?php if (!$projectInfo['gitInfo']['available'] || $projectInfo['gitBranch'] === 'Git غير مثبت'): ?>
        <div
            style="position: fixed; bottom: 20px; right: 20px; background: #fed7d7; color: #742a2a; padding: 1rem; border-radius: 0.5rem; max-width: 400px; box-shadow: var(--shadow-lg); z-index: 100;">
            <strong>⚠️ تنبيه: Git غير متاح</strong>
            <p style="margin-top: 0.5rem; font-size: 0.9rem;">
                للاستفادة من ميزات Git، يرجى:
                <br>1. التأكد من تثبيت Git من <a href="https://git-scm.com/" target="_blank">git-scm.com</a>
                <br>2. <strong>إعادة تشغيل XAMPP</strong> (مهم!)
                <br>3. التأكد من إضافة Git إلى PATH أثناء التثبيت
                <br>4. تحديث الصفحة
                <br><br>
                <small>💡 نصيحة: إعادة تشغيل XAMPP عادة ما يحل المشكلة</small>
            </p>
        </div>
    <?php elseif (!$projectInfo['gitInfo']['hasConfig']): ?>
        <div
            style="position: fixed; bottom: 20px; right: 20px; background: #fefcbf; color: #744210; padding: 1rem; border-radius: 0.5rem; max-width: 400px; box-shadow: var(--shadow-lg); z-index: 100; border-right: 4px solid #f6e05e;">
            <strong>⚠️ تنبيه: Git يحتاج إلى تكوين</strong>
            <p style="margin-top: 0.5rem; font-size: 0.9rem;">
                يجب تكوين Git قبل استخدامه. قم بإدخال اسمك وبريدك الإلكتروني:
                <br><br>
                <button onclick="showGitConfigModal()"
                    style="background: #744210; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; font-family: inherit;">
                    تكوين Git الآن
                </button>
            </p>
        </div>
    <?php endif; ?>

    <script>
        // Global variables
        let currentAction = null;
        let isProcessing = false;

        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            toast.style.display = 'block';

            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        // Execute action
        async function executeAction(action, additionalData = {}) {
            if (isProcessing) {
                showToast('يرجى الانتظار حتى اكتمال العملية الحالية', 'error');
                return;
            }

            const card = event.currentTarget;
            const spinner = card.querySelector('.spinner');
            const modal = document.getElementById('outputModal');
            const modalTitle = document.getElementById('modalTitleText');
            const modalIcon = document.getElementById('modalIcon');
            const modalBody = document.getElementById('modalBody');

            // Set processing state
            isProcessing = true;
            currentAction = action;

            // Show loading
            card.classList.add('loading');
            spinner.style.display = 'block';

            // Show modal
            modal.style.display = 'block';
            modalTitle.textContent = 'جاري التنفيذ...';
            modalIcon.textContent = '⏳';
            modalBody.innerHTML = `<div style="text-align: center; color: #a0aec0;">
                <div style="font-size: 2rem; margin-bottom: 1rem;">⌛</div>
                <div>يرجى الانتظار، جاري تنفيذ العملية...</div>
            </div>`;

            try {
                // Prepare form data
                const formData = new FormData();
                formData.append('action', action);
                formData.append('ajax', '1');

                // Add additional data
                for (const [key, value] of Object.entries(additionalData)) {
                    formData.append(key, value);
                }

                // Send request
                const response = await fetch('manage.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                // Check if Git configuration is needed
                if (data.needsConfig) {
                    modalIcon.textContent = '⚠️';
                    modalTitle.textContent = 'يجب تكوين Git أولاً';
                    modalTitle.style.color = 'var(--warning)';
                    modalBody.innerHTML = `
                        <div style="white-space: pre-wrap;">${data.output}</div>
                        <div style="margin-top: 1.5rem; text-align: center;">
                            <button onclick="closeModal(); showGitConfigModal();" 
                                    style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; cursor: pointer; font-size: 1rem;">
                                تكوين Git الآن
                            </button>
                        </div>
                    `;
                    return;
                }

                // Update modal with results
                if (data.success) {
                    modalIcon.textContent = '✅';
                    modalTitle.textContent = data.message || 'تمت العملية بنجاح';
                    modalTitle.style.color = 'var(--success)';
                } else {
                    modalIcon.textContent = '❌';
                    modalTitle.textContent = data.message || 'فشلت العملية';
                    modalTitle.style.color = 'var(--danger)';
                }

                // Display output
                modalBody.textContent = data.output || 'لا توجد مخرجات';

                // Show toast
                showToast(data.message || (data.success ? 'تمت العملية بنجاح' : 'فشلت العملية'),
                    data.success ? 'success' : 'error');

                // Refresh status if needed
                if (action === 'push_to_github' || action === 'sync_from_live') {
                    setTimeout(refreshStatus, 1000);
                }

            } catch (error) {
                modalIcon.textContent = '❌';
                modalTitle.textContent = 'خطأ في الاتصال';
                modalTitle.style.color = 'var(--danger)';
                modalBody.textContent = `خطأ: ${error.message}`;
                showToast('حدث خطأ في الاتصال', 'error');
            } finally {
                // Reset loading state
                card.classList.remove('loading');
                spinner.style.display = 'none';
                isProcessing = false;
            }
        }

        // Show Git commit dialog
        function showGitDialog() {
            const commitMessage = prompt('أدخل رسالة الـ commit (اتركها فارغة للرسالة التلقائية):');

            if (commitMessage !== null) {
                executeAction('push_to_github', {
                    commit_message: commitMessage
                });
            }
        }

        // Git configuration modal functions
        function showGitConfigModal() {
            document.getElementById('gitConfigModal').style.display = 'block';

            // Check current config
            checkGitConfig();
        }

        function closeGitConfigModal() {
            document.getElementById('gitConfigModal').style.display = 'none';
        }

        async function checkGitConfig() {
            try {
                const formData = new FormData();
                formData.append('action', 'check_git_config');
                formData.append('ajax', '1');

                const response = await fetch('manage.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.data) {
                    document.getElementById('gitName').value = data.data.name || '';
                    document.getElementById('gitEmail').value = data.data.email || '';
                }
            } catch (error) {
                console.error('Error checking git config:', error);
            }
        }

        async function saveGitConfig() {
            const name = document.getElementById('gitName').value.trim();
            const email = document.getElementById('gitEmail').value.trim();
            const saveButton = document.getElementById('saveGitConfigBtn');

            if (!name || !email) {
                showToast('يرجى إدخال الاسم والبريد الإلكتروني', 'error');
                return;
            }

            // Show loading state
            const originalText = saveButton.textContent;
            saveButton.disabled = true;
            saveButton.textContent = 'جاري الحفظ...';

            try {
                const formData = new FormData();
                formData.append('action', 'set_git_config');
                formData.append('git_name', name);
                formData.append('git_email', email);
                formData.append('ajax', '1');

                const response = await fetch('manage.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showToast('تم حفظ تكوين Git بنجاح', 'success');
                    closeGitConfigModal();

                    // Refresh status to show the new Git user
                    refreshStatus();

                    // Retry the git action if we came from a push
                    if (currentAction === 'push_to_github') {
                        setTimeout(() => showGitDialog(), 500);
                    }
                } else {
                    showToast(data.message || 'فشل حفظ التكوين', 'error');
                }
            } catch (error) {
                showToast('خطأ في حفظ التكوين', 'error');
            } finally {
                // Restore button state
                saveButton.disabled = false;
                saveButton.textContent = originalText;
            }
        }

        // Confirm deployment
        function confirmDeploy() {
            const message = `⚠️ تحذير: النشر على الخادم المباشر

هل أنت متأكد من رغبتك في النشر؟

تأكد من:
✓ اختبار جميع التغييرات محلياً
✓ رفع التغييرات إلى GitHub
✓ أخذ نسخة احتياطية من قاعدة البيانات

اكتب "نعم" للمتابعة:`;

            const confirmation = prompt(message);

            if (confirmation && confirmation.toLowerCase() === 'نعم') {
                executeAction('deploy_to_live', { confirm: 'true' });
            } else if (confirmation !== null) {
                showToast('تم إلغاء عملية النشر', 'error');
            }
        }

        // Refresh project status
        async function refreshStatus() {
            try {
                const formData = new FormData();
                formData.append('action', 'get_project_info');
                formData.append('ajax', '1');

                const response = await fetch('manage.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.data) {
                    // Update status items
                    const statusItems = document.querySelectorAll('.status-item');
                    const statusData = [
                        data.data.environment,
                        data.data.projectPath,
                        data.data.phpVersion,
                        data.data.gitBranch,
                        data.data.gitStatus,
                        data.data.gitInfo.hasConfig ? data.data.gitInfo.userName : 'غير مكوّن'
                    ];

                    statusItems.forEach((item, index) => {
                        const span = item.querySelector('span');
                        if (span && statusData[index] !== undefined) {
                            // Special handling for Git status
                            if (index === 4) { // Git status
                                span.className = statusData[index] === 'نظيف' ? 'badge badge-success' : 'badge badge-warning';
                                span.textContent = statusData[index];
                            } else if (index === 3) { // Git branch
                                span.className = 'badge badge-info';
                                span.textContent = statusData[index];
                            } else if (index === 5) { // Git user
                                if (data.data.gitInfo.hasConfig) {
                                    span.innerHTML = statusData[index];
                                } else {
                                    span.innerHTML = '<span style="color: #e53e3e;">غير مكوّن</span> <a href="#" onclick="showGitConfigModal(); return false;" style="color: var(--primary);">تكوين</a>';
                                }
                            } else {
                                span.textContent = statusData[index];
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error refreshing status:', error);
            }
        }

        // Close modal
        function closeModal() {
            document.getElementById('outputModal').style.display = 'none';
        }

        // Close modal on outside click
        window.onclick = function (event) {
            const modal = document.getElementById('outputModal');
            const gitModal = document.getElementById('gitConfigModal');

            if (event.target === modal && !isProcessing) {
                closeModal();
            } else if (event.target === gitModal) {
                closeGitConfigModal();
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !isProcessing) {
                if (document.getElementById('gitConfigModal').style.display === 'block') {
                    closeGitConfigModal();
                } else {
                    closeModal();
                }
            }
        });

        // Auto-refresh status every 30 seconds
        setInterval(refreshStatus, 30000);

        // Initial animation
        document.addEventListener('DOMContentLoaded', function () {
            // Add stagger effect to cards
            const cards = document.querySelectorAll('.action-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>

    <!-- Footer with debug link -->
    <div style="text-align: center; margin-top: 3rem; padding-bottom: 2rem;">
        <a href="?debug=1" style="color: rgba(255,255,255,0.6); font-size: 0.9rem; text-decoration: none;">
            🐛 وضع التشخيص
        </a>
    </div>
</body>

</html>