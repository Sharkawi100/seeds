<?php
// Security check
if ($_SERVER['HTTP_HOST'] !== 'localhost') {
    die('<h1 style="color:red;text-align:center;margin-top:50px;">⚠️ This page can only be accessed from localhost!</h1>');
}

// Fix Git ownership issue at the start
shell_exec('git config --global --add safe.directory C:/xampp/htdocs/juzoor-quiz 2>&1');

// Configuration
$projectPath = 'C:\xampp\htdocs\juzoor-quiz';
$githubRepo = 'https://github.com/Sharkawi100/seeds';

// Function to execute command and return output
function executeCommand($command, $workingDir = null)
{
    if ($workingDir) {
        $command = "cd /d \"$workingDir\" && $command";
    }
    return shell_exec($command . ' 2>&1');
}

// Get project status
function getProjectStatus()
{
    global $projectPath;

    $status = [
        'git_branch' => trim(executeCommand('git branch --show-current', $projectPath)) ?: 'unknown',
        'git_status' => executeCommand('git status --porcelain', $projectPath),
        'uncommitted_changes' => false,
        'last_commit' => trim(executeCommand('git log -1 --pretty=format:"%h - %s (%cr)"', $projectPath)),
        'remote_url' => trim(executeCommand('git config --get remote.origin.url', $projectPath))
    ];

    $status['uncommitted_changes'] = !empty($status['git_status']);

    return $status;
}

// Handle AJAX requests
if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
    header('Content-Type: application/json');

    $response = ['success' => false, 'message' => '', 'output' => ''];

    switch ($_POST['action']) {
        case 'sync_from_live':
            $output = executeCommand('sync-from-live.bat', $projectPath);
            $response['success'] = strpos($output, 'Sync Complete') !== false;
            $response['output'] = $output;
            $response['message'] = $response['success'] ? 'تمت المزامنة بنجاح!' : 'فشلت المزامنة';
            break;

        case 'push_to_github':
            $status = getProjectStatus();

            if (!$status['uncommitted_changes']) {
                $response['message'] = 'لا توجد تغييرات للرفع!';
                $response['output'] = 'Working directory is clean.';
            } else {
                // Add all changes
                $output = executeCommand('git add .', $projectPath);

                // Commit with message
                $commitMessage = $_POST['commit_message'] ?? 'Update from manage.php';
                $output .= "\n" . executeCommand("git commit -m \"$commitMessage\"", $projectPath);

                // Push to GitHub
                $output .= "\n" . executeCommand('git push origin main', $projectPath);

                $response['success'] = strpos($output, 'error') === false && strpos($output, 'fatal') === false;
                $response['output'] = $output;
                $response['message'] = $response['success'] ? 'تم الرفع إلى GitHub بنجاح!' : 'فشل الرفع إلى GitHub';
            }
            break;

        case 'clear_cache':
            $output = executeCommand('php artisan cache:clear', $projectPath);
            $output .= "\n" . executeCommand('php artisan config:clear', $projectPath);
            $output .= "\n" . executeCommand('php artisan view:clear', $projectPath);
            $output .= "\n" . executeCommand('php artisan route:clear', $projectPath);

            $response['success'] = true;
            $response['output'] = $output;
            $response['message'] = 'تم مسح الذاكرة المؤقتة بنجاح!';
            break;

        case 'npm_build':
            $output = executeCommand('npm run build', $projectPath);
            $response['success'] = strpos($output, 'built in') !== false;
            $response['output'] = $output;
            $response['message'] = $response['success'] ? 'تم البناء بنجاح!' : 'فشل البناء';
            break;

        case 'backup_database':
            $output = executeCommand('backup-database.bat', $projectPath);
            $response['success'] = strpos($output, 'backup saved') !== false;
            $response['output'] = $output;
            $response['message'] = $response['success'] ? 'تم الحفظ الاحتياطي بنجاح!' : 'فشل الحفظ الاحتياطي';
            break;

        case 'deploy_to_live':
            if ($_POST['confirm'] === 'true') {
                $output = executeCommand('echo yes | deploy-to-live.bat', $projectPath);
                $response['success'] = strpos($output, 'Deployment complete') !== false;
                $response['output'] = $output;
                $response['message'] = $response['success'] ? 'تم النشر بنجاح!' : 'فشل النشر';
            } else {
                $response['message'] = 'يجب التأكيد قبل النشر!';
            }
            break;
    }

    echo json_encode($response);
    exit;
}

// Get current status
$projectStatus = getProjectStatus();
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة مشروع جُذور</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background: #f0f2f5;
            color: #1a1a1a;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .status-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        
        .status-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .status-card h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .status-card .status-value {
            font-size: 1.1rem;
            color: #333;
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            font-family: monospace;
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 10px;
        }
        
        .status-indicator.green { background: #10b981; }
        .status-indicator.red { background: #ef4444; }
        .status-indicator.yellow { background: #f59e0b; }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .action-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .action-card h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .action-card p {
            color: #666;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .btn {
            width: 100%;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .output-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            overflow-y: auto;
        }
        
        .output-content {
            background: white;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            max-width: 800px;
            width: 90%;
            position: relative;
        }
        
        .output-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .output-body {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 10px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 14px;
            line-height: 1.6;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }
        
        .close-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        .commit-message-input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .commit-message-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .git-status {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-family: monospace;
            font-size: 14px;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .quick-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .quick-link {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            text-align: center;
            transition: all 0.3s;
        }
        
        .quick-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌱 إدارة مشروع جُذور</h1>
            <p>لوحة تحكم متكاملة لإدارة التطوير والنشر</p>
        </div>

        <div id="alert" class="alert"></div>

        <div class="status-grid">
            <div class="status-card">
                <h3>📁 حالة المشروع</h3>
                <div class="status-value">
                    Git Branch: <?php echo $projectStatus['git_branch']; ?>
                    <span class="status-indicator <?php echo $projectStatus['uncommitted_changes'] ? 'yellow' : 'green'; ?>"></span>
                </div>
                <?php if ($projectStatus['uncommitted_changes']): ?>
                    <div class="git-status">
                        <strong>تغييرات غير محفوظة:</strong><br>
                        <?php echo nl2br(htmlspecialchars($projectStatus['git_status'])); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="status-card">
                <h3>📝 آخر commit</h3>
                <div class="status-value">
                    <?php echo htmlspecialchars($projectStatus['last_commit'] ?: 'لا يوجد'); ?>
                </div>
            </div>

            <div class="status-card">
                <h3>🔗 GitHub Repository</h3>
                <div class="status-value">
                    <?php echo htmlspecialchars($projectStatus['remote_url'] ?: 'غير متصل'); ?>
                </div>
            </div>
        </div>

        <div class="actions-grid">
            <!-- Sync from Live -->
            <div class="action-card">
                <h3>⬇️ مزامنة من المباشر</h3>
                <p>جلب آخر التحديثات من الخادم المباشر إلى المحلي</p>
                <button class="btn btn-info" onclick="executeAction('sync_from_live')">
                    <span class="spinner"></span>
                    تنفيذ المزامنة
                </button>
            </div>

            <!-- GitHub Push -->
            <div class="action-card">
                <h3>📤 رفع إلى GitHub</h3>
                <p>حفظ التغييرات في مستودع GitHub</p>
                <?php if ($projectStatus['uncommitted_changes']): ?>
                    <input type="text" 
                           id="commit-message" 
                           class="commit-message-input" 
                           placeholder="رسالة الـ commit..." 
                           value="تحديث من لوحة التحكم">
                <?php endif; ?>
                <button class="btn btn-primary" 
                        onclick="pushToGitHub()"
                        <?php echo !$projectStatus['uncommitted_changes'] ? 'disabled' : ''; ?>>
                    <span class="spinner"></span>
                    <?php echo $projectStatus['uncommitted_changes'] ? 'رفع التغييرات' : 'لا توجد تغييرات'; ?>
                </button>
            </div>

            <!-- Clear Cache -->
            <div class="action-card">
                <h3>🗑️ مسح الذاكرة المؤقتة</h3>
                <p>مسح جميع ملفات Laravel cache</p>
                <button class="btn btn-warning" onclick="executeAction('clear_cache')">
                    <span class="spinner"></span>
                    مسح الكاش
                </button>
            </div>

            <!-- Build Assets -->
            <div class="action-card">
                <h3>🏗️ بناء الملفات</h3>
                <p>تشغيل npm run build لبناء ملفات CSS/JS</p>
                <button class="btn btn-info" onclick="executeAction('npm_build')">
                    <span class="spinner"></span>
                    بناء الملفات
                </button>
            </div>

            <!-- Database Backup -->
            <div class="action-card">
                <h3>💾 نسخة احتياطية</h3>
                <p>حفظ نسخة احتياطية من قاعدة البيانات المحلية</p>
                <button class="btn btn-success" onclick="executeAction('backup_database')">
                    <span class="spinner"></span>
                    إنشاء نسخة احتياطية
                </button>
            </div>

            <!-- Deploy to Live -->
            <div class="action-card">
                <h3>🚀 نشر على المباشر</h3>
                <p>نشر التطبيق على الخادم المباشر</p>
                <button class="btn btn-danger" onclick="deployToLive()">
                    <span class="spinner"></span>
                    نشر التطبيق
                </button>
            </div>
        </div>

        <div class="quick-links">
            <a href="http://localhost/juzoor-quiz" class="quick-link" target="_blank">
                🏠 الموقع المحلي
            </a>
            <a href="https://iseraj.com/roots" class="quick-link" target="_blank">
                🌐 الموقع المباشر
            </a>
            <a href="http://localhost/phpmyadmin" class="quick-link" target="_blank">
                🗄️ phpMyAdmin
            </a>
            <a href="https://github.com/Sharkawi100/seeds" class="quick-link" target="_blank">
                📚 GitHub Repo
            </a>
        </div>
    </div>

    <!-- Output Modal -->
    <div id="output-modal" class="output-modal">
        <div class="output-content">
            <div class="output-header">
                <h3 id="output-title">نتيجة التنفيذ</h3>
                <button class="close-btn" onclick="closeModal()">إغلاق</button>
            </div>
            <div id="output-body" class="output-body"></div>
        </div>
    </div>

    <script>
        function showAlert(message, type = 'success') {
            const alert = document.getElementById('alert');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alert.style.display = 'block';
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }

        function showModal(title, content) {
            document.getElementById('output-title').textContent = title;
            document.getElementById('output-body').textContent = content;
            document.getElementById('output-modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('output-modal').style.display = 'none';
        }

        function setLoading(button, loading) {
            const spinner = button.querySelector('.spinner');
            spinner.style.display = loading ? 'inline-block' : 'none';
            button.disabled = loading;
        }

        async function executeAction(action) {
            const button = event.target;
            setLoading(button, true);

            try {
                const formData = new FormData();
                formData.append('ajax', 'true');
                formData.append('action', action);

                const response = await fetch('manage.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                } else {
                    showAlert(result.message, 'error');
                }
                
                if (result.output) {
                    showModal(`نتيجة: ${action}`, result.output);
                }
                
                // Reload page to update status
                if (action === 'push_to_github' && result.success) {
                    setTimeout(() => location.reload(), 2000);
                }
            } catch (error) {
                showAlert('حدث خطأ: ' + error.message, 'error');
            } finally {
                setLoading(button, false);
            }
        }

        async function pushToGitHub() {
            const commitMessage = document.getElementById('commit-message')?.value || 'Update from manage.php';
            const button = event.target;
            setLoading(button, true);

            try {
                const formData = new FormData();
                formData.append('ajax', 'true');
                formData.append('action', 'push_to_github');
                formData.append('commit_message', commitMessage);

                const response = await fetch('manage.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(result.message, 'error');
                }
                
                if (result.output) {
                    showModal('نتيجة رفع GitHub', result.output);
                }
            } catch (error) {
                showAlert('حدث خطأ: ' + error.message, 'error');
            } finally {
                setLoading(button, false);
            }
        }

        async function deployToLive() {
            if (!confirm('هل أنت متأكد من نشر التطبيق على الخادم المباشر؟\n\nتأكد من:\n- اختبار جميع الميزات محلياً\n- رفع التغييرات إلى GitHub\n- أخذ نسخة احتياطية')) {
                return;
            }

            const button = event.target;
            setLoading(button, true);

            try {
                const formData = new FormData();
                formData.append('ajax', 'true');
                formData.append('action', 'deploy_to_live');
                formData.append('confirm', 'true');

                const response = await fetch('manage.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                } else {
                    showAlert(result.message, 'error');
                }
                
                if (result.output) {
                    showModal('نتيجة النشر', result.output);
                }
            } catch (error) {
                showAlert('حدث خطأ: ' + error.message, 'error');
            } finally {
                setLoading(button, false);
            }
        }

        // Close modal on click outside
        window.onclick = function(event) {
            const modal = document.getElementById('output-modal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>