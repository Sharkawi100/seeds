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

// Git configuration - try multiple possible paths
$gitPaths = [
    'C:\\Program Files\\Git\\bin\\git.exe',
    'C:\\Program Files (x86)\\Git\\bin\\git.exe',
    'C:\\Git\\bin\\git.exe',
    'git' // fallback to PATH
];

$gitExecutable = 'git';
foreach ($gitPaths as $path) {
    if (file_exists($path) || $path === 'git') {
        $gitExecutable = $path;
        if ($path !== 'git')
            break;
    }
}
define('GIT_PATH', $gitExecutable);

// Initialize Git config for safety
@exec(GIT_PATH . ' config --global --add safe.directory ' . escapeshellarg(PROJECT_PATH) . ' 2>&1');

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
            'C:\\Program Files\\Git\\bin',
            'C:\\Program Files (x86)\\Git\\bin',
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
        // For Windows, wrap commands in cmd /c
        if (stripos(PHP_OS, 'WIN') === 0) {
            $fullCommand = 'cmd /c "' . str_replace('"', '""', $command) . '"';
        } else {
            $fullCommand = $command;
        }

        $descriptors = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w']   // stderr
        ];

        $process = proc_open(
            $fullCommand,
            $descriptors,
            $pipes,
            $this->workingDir,
            $this->env
        );

        if (!is_resource($process)) {
            return [
                'success' => false,
                'output' => '',
                'error' => 'Failed to execute command: ' . $command,
                'exitCode' => -1
            ];
        }

        // Send input if provided
        if ($input !== null) {
            fwrite($pipes[0], $input);
        }
        fclose($pipes[0]);

        // Get output and error
        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        return [
            'success' => $exitCode === 0,
            'output' => $output,
            'error' => $error,
            'exitCode' => $exitCode,
            'command' => $command
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
        $result = $this->executor->execute(GIT_PATH . ' --version');
        return $result['success'];
    }

    public function isGitRepository()
    {
        $result = $this->executor->execute(GIT_PATH . ' rev-parse --git-dir');
        return $result['success'];
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
            // Try to initialize Git repository
            $initResult = $this->executor->execute(GIT_PATH . ' init');
            if (!$initResult['success']) {
                return ['status' => 'ليس مستودع Git', 'hasChanges' => false, 'details' => $initResult['error']];
            }
        }

        $result = $this->executor->execute(GIT_PATH . ' status --porcelain');
        if (!$result['success']) {
            return [
                'status' => 'خطأ في Git',
                'hasChanges' => false,
                'details' => $result['error']
            ];
        }

        $output = trim($result['output']);
        return [
            'status' => empty($output) ? 'نظيف' : 'يوجد تغييرات',
            'hasChanges' => !empty($output),
            'changes' => $output,
            'details' => ''
        ];
    }

    public function getRemoteUrl()
    {
        $result = $this->executor->execute(GIT_PATH . ' config --get remote.origin.url');
        if ($result['success'] && !empty(trim($result['output']))) {
            $url = trim($result['output']);
            // Convert SSH URL to HTTPS for browser
            if (strpos($url, 'git@github.com:') === 0) {
                $url = str_replace('git@github.com:', 'https://github.com/', $url);
                $url = str_replace('.git', '', $url);
            }
            return $url;
        }
        return '#';
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
        $gitInfo = [
            'available' => $this->git->isGitAvailable(),
            'isRepo' => $this->git->isGitRepository(),
            'executable' => GIT_PATH
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
                'output' => ''
            ];
        }

        $result = $this->executor->execute($batFile);
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
                'output' => ''
            ];
        }

        $result = $this->executor->execute($batFile);
        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'تم النسخ الاحتياطي بنجاح' : 'فشل النسخ الاحتياطي',
            'output' => $result['output'] . "\n" . $result['error']
        ];
    }

    public function clearCache()
    {
        $commands = [
            'php artisan cache:clear',
            'php artisan config:clear',
            'php artisan view:clear',
            'php artisan route:clear'
        ];

        $result = $this->executor->executeSequence($commands);

        $output = "تنفيذ أوامر مسح الذاكرة المؤقتة:\n";
        foreach ($result['results'] as $idx => $cmdResult) {
            $output .= "\n{$commands[$idx]}:\n";
            $output .= $cmdResult['output'] ?: 'تم التنفيذ بنجاح';
            $output .= "\n";
        }

        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'تم مسح جميع الذاكرة المؤقتة' : 'حدث خطأ أثناء مسح الذاكرة المؤقتة',
            'output' => $output
        ];
    }

    public function npmInstall()
    {
        $result = $this->executor->execute('npm install');
        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'تم تثبيت الحزم بنجاح' : 'فشل تثبيت الحزم',
            'output' => $result['output'] . "\n" . $result['error']
        ];
    }

    public function npmBuild()
    {
        $result = $this->executor->execute('npm run build');
        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'تم بناء الملفات بنجاح' : 'فشل بناء الملفات',
            'output' => $result['output'] . "\n" . $result['error']
        ];
    }

    public function deployToLive()
    {
        $batFile = PROJECT_PATH . '\\deploy-to-live.bat';
        if (!file_exists($batFile)) {
            return [
                'success' => false,
                'message' => 'ملف deploy-to-live.bat غير موجود',
                'output' => ''
            ];
        }

        // Execute with confirmation
        $result = $this->executor->execute($batFile, "yes\n");
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
                    <strong>وقت الخادم</strong>
                    <span><?php echo $projectInfo['serverTime']; ?></span>
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

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

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
                    document.querySelectorAll('.status-item').forEach((item, index) => {
                        const value = Object.values(data.data)[index];
                        const span = item.querySelector('span');
                        if (span && value !== undefined) {
                            // Update badge classes for git status
                            if (item.querySelector('strong').textContent === 'حالة Git') {
                                span.className = value === 'نظيف' ? 'badge badge-success' : 'badge badge-warning';
                            }
                            span.textContent = value;
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
            if (event.target === modal && !isProcessing) {
                closeModal();
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !isProcessing) {
                closeModal();
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
</body>

</html>