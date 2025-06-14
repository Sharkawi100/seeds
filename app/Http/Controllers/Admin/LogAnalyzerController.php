<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class LogAnalyzerController extends Controller
{
    /**
     * Show log analysis dashboard
     */
    public function index(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return view('admin.logs.analyzer', [
                'errors' => [],
                'summary' => [
                    'total_errors' => 0,
                    'critical_count' => 0,
                    'error_count' => 0,
                    'warning_count' => 0,
                    'file_size' => 0,
                    'last_error' => null
                ],
                'recommendations' => []
            ]);
        }

        // Get analysis parameters
        $hours = $request->get('hours', 24); // Last 24 hours by default
        $limit = $request->get('limit', 100); // Max 100 errors

        $analysis = $this->analyzeLogs($logPath, $hours, $limit);

        return view('admin.logs.analyzer', $analysis);
    }

    /**
     * Analyze log file and extract key information
     */
    private function analyzeLogs($logPath, $hours = 24, $limit = 100)
    {
        $fileSize = File::size($logPath);
        $cutoffTime = Carbon::now()->subHours($hours);

        // Read log file in chunks to handle large files
        $errors = [];
        $handle = fopen($logPath, 'r');

        if ($handle) {
            $currentError = null;
            $lineCount = 0;

            while (($line = fgets($handle)) !== false && count($errors) < $limit) {
                $lineCount++;

                // Check if this is a new log entry
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                    // Save previous error if exists
                    if ($currentError && $this->isWithinTimeRange($currentError['timestamp'], $cutoffTime)) {
                        $errors[] = $currentError;
                    }

                    // Parse new error
                    $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $matches[1]);

                    if ($timestamp->gte($cutoffTime)) {
                        $currentError = $this->parseLogLine($line, $timestamp);
                    } else {
                        $currentError = null;
                    }
                } elseif ($currentError) {
                    // Append continuation line to current error
                    $currentError['full_message'] .= "\n" . trim($line);

                    // Extract more details from stack trace
                    if (strpos($line, 'app/Http/Controllers/') !== false) {
                        preg_match('/app\/Http\/Controllers\/([^:]+)/', $line, $controllerMatch);
                        if (isset($controllerMatch[1])) {
                            $currentError['controller'] = $controllerMatch[1];
                        }
                    }

                    if (strpos($line, 'resources/views/') !== false) {
                        preg_match('/resources\/views\/([^)]+)/', $line, $viewMatch);
                        if (isset($viewMatch[1])) {
                            $currentError['view'] = $viewMatch[1];
                        }
                    }
                }
            }

            // Don't forget the last error
            if ($currentError && $this->isWithinTimeRange($currentError['timestamp'], $cutoffTime)) {
                $errors[] = $currentError;
            }

            fclose($handle);
        }

        // Analyze and group errors
        $groupedErrors = $this->groupErrors($errors);
        $summary = $this->generateSummary($errors, $fileSize);
        $recommendations = $this->generateRecommendations($groupedErrors);

        return [
            'errors' => $groupedErrors,
            'summary' => $summary,
            'recommendations' => $recommendations,
            'hours' => $hours,
            'limit' => $limit
        ];
    }

    /**
     * Parse a single log line
     */
    private function parseLogLine($line, $timestamp)
    {
        // Extract log level and environment
        preg_match('/\] (\w+)\.(\w+): (.+)/', $line, $matches);

        $level = $matches[1] ?? 'unknown';
        $environment = $matches[2] ?? 'unknown';
        $message = $matches[3] ?? $line;

        // Extract exception class if present
        $exceptionClass = 'Unknown';
        if (preg_match('/(\w+Exception|\w+Error)/', $message, $exceptionMatch)) {
            $exceptionClass = $exceptionMatch[1];
        }

        // Extract file and line if present
        $file = null;
        $lineNumber = null;
        if (preg_match('/at (.+):(\d+)/', $message, $fileMatch)) {
            $file = basename($fileMatch[1]);
            $lineNumber = $fileMatch[2];
        }

        return [
            'timestamp' => $timestamp,
            'level' => strtoupper($level),
            'environment' => $environment,
            'message' => $message,
            'full_message' => $line,
            'exception_class' => $exceptionClass,
            'file' => $file,
            'line_number' => $lineNumber,
            'controller' => null,
            'view' => null,
            'hash' => md5($this->normalizeError($message))
        ];
    }

    /**
     * Group similar errors together
     */
    private function groupErrors($errors)
    {
        $grouped = [];

        foreach ($errors as $error) {
            $hash = $error['hash'];

            if (!isset($grouped[$hash])) {
                $grouped[$hash] = [
                    'sample_error' => $error,
                    'count' => 0,
                    'first_seen' => $error['timestamp'],
                    'last_seen' => $error['timestamp'],
                    'affected_files' => [],
                    'affected_controllers' => [],
                    'affected_views' => []
                ];
            }

            $group = &$grouped[$hash];
            $group['count']++;
            $group['last_seen'] = max($group['last_seen'], $error['timestamp']);
            $group['first_seen'] = min($group['first_seen'], $error['timestamp']);

            if ($error['file'])
                $group['affected_files'][] = $error['file'];
            if ($error['controller'])
                $group['affected_controllers'][] = $error['controller'];
            if ($error['view'])
                $group['affected_views'][] = $error['view'];
        }

        // Remove duplicates and sort by count
        foreach ($grouped as &$group) {
            $group['affected_files'] = array_unique($group['affected_files']);
            $group['affected_controllers'] = array_unique($group['affected_controllers']);
            $group['affected_views'] = array_unique($group['affected_views']);
        }

        // Sort by count (most frequent first)
        uasort($grouped, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        return $grouped;
    }

    /**
     * Generate summary statistics
     */
    private function generateSummary($errors, $fileSize)
    {
        $levels = array_count_values(array_column($errors, 'level'));

        $lastError = null;
        if (!empty($errors)) {
            $lastError = max(array_column($errors, 'timestamp'));
        }

        return [
            'total_errors' => count($errors),
            'critical_count' => $levels['CRITICAL'] ?? 0,
            'error_count' => $levels['ERROR'] ?? 0,
            'warning_count' => $levels['WARNING'] ?? 0,
            'info_count' => $levels['INFO'] ?? 0,
            'file_size' => $fileSize,
            'file_size_mb' => round($fileSize / 1024 / 1024, 2),
            'last_error' => $lastError
        ];
    }

    /**
     * Generate recommendations based on error patterns
     */
    private function generateRecommendations($groupedErrors)
    {
        $recommendations = [];

        foreach ($groupedErrors as $group) {
            $error = $group['sample_error'];
            $message = $error['message'];

            // Route not found errors
            if (strpos($message, 'Route') !== false && strpos($message, 'not defined') !== false) {
                $recommendations[] = [
                    'type' => 'route_error',
                    'priority' => 'high',
                    'title' => 'مشكلة في الروابط (Routes)',
                    'description' => 'هناك روابط غير معرّفة تسبب أخطاء',
                    'solution' => 'تحقق من ملف routes/web.php وتأكد من وجود جميع الروابط المطلوبة',
                    'count' => $group['count']
                ];
            }

            // Database errors
            if (strpos($message, 'database') !== false || strpos($message, 'MySQL') !== false || strpos($message, 'SQL') !== false) {
                $recommendations[] = [
                    'type' => 'database_error',
                    'priority' => 'critical',
                    'title' => 'مشكلة في قاعدة البيانات',
                    'description' => 'هناك أخطاء في الاتصال أو استعلامات قاعدة البيانات',
                    'solution' => 'تحقق من إعدادات قاعدة البيانات في .env وتأكد من صحة الاتصال',
                    'count' => $group['count']
                ];
            }

            // Class not found errors
            if (strpos($message, 'Class') !== false && strpos($message, 'not found') !== false) {
                $recommendations[] = [
                    'type' => 'class_error',
                    'priority' => 'high',
                    'title' => 'ملفات مفقودة (Class Not Found)',
                    'description' => 'هناك ملفات أو كلاسات مفقودة',
                    'solution' => 'تحقق من وجود الملفات المطلوبة أو قم بتشغيل composer dump-autoload',
                    'count' => $group['count']
                ];
            }

            // View errors
            if (strpos($message, 'View') !== false && (strpos($message, 'not found') !== false || strpos($message, 'does not exist') !== false)) {
                $recommendations[] = [
                    'type' => 'view_error',
                    'priority' => 'medium',
                    'title' => 'صفحات مفقودة (Views)',
                    'description' => 'هناك صفحات مفقودة في مجلد resources/views',
                    'solution' => 'تحقق من وجود ملفات Blade المطلوبة في resources/views',
                    'count' => $group['count']
                ];
            }

            // Permission errors
            if (strpos($message, 'Permission') !== false || strpos($message, 'forbidden') !== false) {
                $recommendations[] = [
                    'type' => 'permission_error',
                    'priority' => 'medium',
                    'title' => 'مشكلة في الصلاحيات',
                    'description' => 'هناك مشاكل في صلاحيات الملفات أو المجلدات',
                    'solution' => 'تحقق من صلاحيات مجلدات storage و bootstrap/cache',
                    'count' => $group['count']
                ];
            }

            // Memory errors
            if (strpos($message, 'memory') !== false || strpos($message, 'Memory') !== false) {
                $recommendations[] = [
                    'type' => 'memory_error',
                    'priority' => 'critical',
                    'title' => 'نفاد الذاكرة',
                    'description' => 'الموقع يستهلك ذاكرة أكثر من المسموح',
                    'solution' => 'قم بتحسين الكود أو زيادة memory_limit في PHP',
                    'count' => $group['count']
                ];
            }
        }

        // Remove duplicates and sort by priority
        $recommendations = array_values(array_unique($recommendations, SORT_REGULAR));

        usort($recommendations, function ($a, $b) {
            $priorities = ['critical' => 3, 'high' => 2, 'medium' => 1, 'low' => 0];
            return $priorities[$b['priority']] - $priorities[$a['priority']];
        });

        return array_slice($recommendations, 0, 10); // Top 10 recommendations
    }

    /**
     * Check if timestamp is within specified range
     */
    private function isWithinTimeRange($timestamp, $cutoffTime)
    {
        return $timestamp instanceof Carbon && $timestamp->gte($cutoffTime);
    }

    /**
     * Normalize error message for grouping
     */
    private function normalizeError($message)
    {
        // Remove timestamps, line numbers, and file paths to group similar errors
        $normalized = preg_replace('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', '', $message);
        $normalized = preg_replace('/:\d+/', '', $normalized);
        $normalized = preg_replace('/\/[^\s]+\/([^\/\s]+)/', '$1', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized);

        return trim($normalized);
    }

    /**
     * Clear log file (with backup)
     */
    public function clearLogs(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');

        if (File::exists($logPath)) {
            // Create backup
            $backupPath = storage_path('logs/laravel_backup_' . date('Y_m_d_H_i_s') . '.log');
            File::copy($logPath, $backupPath);

            // Clear current log
            File::put($logPath, '');

            return redirect()->route('admin.logs.analyzer')
                ->with('success', 'تم مسح ملف السجلات وحفظ نسخة احتياطية');
        }

        return redirect()->route('admin.logs.analyzer')
            ->with('error', 'ملف السجلات غير موجود');
    }

    /**
     * Download log file
     */
    public function downloadLogs()
    {
        $logPath = storage_path('logs/laravel.log');

        if (File::exists($logPath)) {
            return response()->download($logPath, 'laravel_' . date('Y_m_d') . '.log');
        }

        return redirect()->route('admin.logs.analyzer')
            ->with('error', 'ملف السجلات غير موجود');
    }
}