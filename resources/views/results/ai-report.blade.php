@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 mb-2 bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                        🎓 التقرير التربوي الذكي
                    </h1>
                    <p class="text-lg text-gray-600">{{ $quiz->title }}</p>
                    
                    <!-- Elegant Report Navigation -->
                    @if($hasExistingReport && $reportNavigation['total_reports'] > 1)
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl p-6 mt-6 border border-purple-100 shadow-sm">
                        <!-- Navigation Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                📑 التقارير السابقة
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full text-sm font-medium">
                                    {{ $reportNavigation['total_reports'] }} تقارير
                                </span>
                            </h3>
                            
                            <!-- Elegant Navigation Controls -->
                            <div class="flex items-center gap-2">
                                @if($reportNavigation['has_previous'])
                                    <a href="{{ route('results.ai-report', ['quiz' => $quiz->id, 'report_index' => $reportNavigation['previous_index']]) }}" 
                                       class="bg-white text-purple-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-purple-50 transition-all duration-300 shadow-md border border-purple-200"
                                       title="التقرير السابق">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="bg-gray-100 text-gray-400 w-10 h-10 rounded-full flex items-center justify-center cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                                
                                <span class="bg-purple-600 text-white px-4 py-2 rounded-full font-bold text-sm mx-2">
                                    {{ $reportNavigation['current_number'] }} / {{ $reportNavigation['total_reports'] }}
                                </span>
                                
                                @if($reportNavigation['has_next'])
                                    <a href="{{ route('results.ai-report', ['quiz' => $quiz->id, 'report_index' => $reportNavigation['next_index']]) }}" 
                                       class="bg-white text-purple-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-purple-50 transition-all duration-300 shadow-md border border-purple-200"
                                       title="التقرير التالي">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @else
                                    <span class="bg-gray-100 text-gray-400 w-10 h-10 rounded-full flex items-center justify-center cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Report Timeline Selector -->
                        <div class="bg-white rounded-xl p-4 border border-gray-100">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <!-- Current Report Metadata -->
                                @if($currentReport)
                                <div class="flex items-center gap-4 text-sm flex-wrap">
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full flex items-center gap-1">
                                        📅 {{ $currentReport->created_at->format('Y-m-d H:i') }}
                                    </span>
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full flex items-center gap-1">
                                        👥 {{ $currentReport->student_count }} طالب
                                    </span>
                                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full flex items-center gap-1">
                                        ⏱️ {{ $reportAge }}
                                    </span>
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full flex items-center gap-1">
                                        🤖 {{ $currentReport->tokens_used > 0 ? 'ذكاء اصطناعي' : 'قالب تلقائي' }}
                                    </span>
                                </div>
                                @endif
                                
                                <!-- Quick Selector -->
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-600 font-medium">الانتقال السريع:</label>
                                    <select id="reportSelector" class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        @foreach($reportNavigation['reports_list'] as $report)
                                            <option value="{{ $report['index'] }}" {{ $report['is_current'] ? 'selected' : '' }}>
                                                تقرير {{ $report['number'] }} - {{ $report['date'] }} ({{ $report['student_count'] }} طالب)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reports Timeline (Visual) -->
                        <div class="mt-4">
                            <div class="flex items-center justify-between relative">
                                <!-- Timeline Line -->
                                <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-gradient-to-r from-purple-200 to-blue-200 -translate-y-1/2"></div>
                                
                                @foreach($reportNavigation['reports_list'] as $report)
                                <a href="{{ route('results.ai-report', ['quiz' => $quiz->id, 'report_index' => $report['index']]) }}" 
                                   class="relative bg-white border-2 {{ $report['is_current'] ? 'border-purple-500 bg-purple-50' : 'border-gray-300 hover:border-purple-300' }} w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 {{ $report['is_current'] ? 'text-purple-600 scale-110' : 'text-gray-600' }}"
                                   title="تقرير {{ $report['number'] }} - {{ $report['age'] }}">
                                    {{ $report['number'] }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex items-center gap-6 mt-4 text-sm text-gray-500">
                        <span>📚 {{ $quiz->subject->name ?? 'عام' }}</span>
                        <span>🎯 الصف {{ $quiz->grade_level }}</span>
                        <span>👥 {{ $results->count() }} طالب</span>
                        <span>📅 {{ now()->format('Y/m/d') }}</span>
                    </div>
                </div>
                
                <!-- Generation Button -->
                <div class="text-center">
                    @if(Auth::user()->canUseAI())
                        @if($hasExistingReport)
                            <div class="space-y-2">
                                <div class="text-sm text-green-600 font-medium">
                                    ✅ تم إنشاء التقرير منذ {{ $reportAge }}
                                </div>
                                <button id="generateReportBtn" class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:from-green-700 hover:to-blue-700 transition-all duration-300 shadow-lg">
                                    🔄 تحديث التقرير
                                </button>
                            </div>
                        @else
                            <button id="generateReportBtn" class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-lg">
                                🤖 إنشاء التقرير الذكي
                            </button>
                        @endif
                        <div class="text-xs text-gray-500 mt-2">
                            متبقي: <span id="quotaCount">{{ $remainingQuota }}</span> من الرصيد الشهري
                        </div>
                    @else
                        <a href="{{ route('subscription.upgrade') }}" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-xl font-bold hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-lg">
                            ⭐ اشترك للحصول على التقارير الذكية
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Section (SQL Data - No AI needed) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Overall Performance -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    📊 الأداء العام
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">المعدل العام:</span>
                        <span class="font-bold text-blue-600">{{ round($overallAverage) }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">نسبة النجاح:</span>
                        <span class="font-bold text-green-600">{{ round($passRate) }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">عدد المتميزين:</span>
                        <span class="font-bold text-purple-600">{{ $excellentCount }}</span>
                    </div>
                </div>
            </div>

            <!-- 4 Roots Performance -->
            @foreach(['jawhar' => '🎯', 'zihn' => '🧠', 'waslat' => '🔗', 'roaya' => '👁️'] as $root => $icon)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    {{ $icon }} 
                    @if($root === 'jawhar') الجوهر
                    @elseif($root === 'zihn') الذهن  
                    @elseif($root === 'waslat') الوصلات
                    @else الرؤية @endif
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>المستوى 1:</span>
                        <span class="font-medium">{{ $rootsPerformance[$root]['level_1'] ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>المستوى 2:</span>
                        <span class="font-medium">{{ $rootsPerformance[$root]['level_2'] ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>المستوى 3:</span>
                        <span class="font-medium">{{ $rootsPerformance[$root]['level_3'] ?? 0 }}%</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between font-bold">
                        <span>المعدل:</span>
                        <span class="text-blue-600">{{ $rootsPerformance[$root]['overall'] ?? 0 }}%</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Debug Info Section (for testing) -->
        <div id="debugInfo" class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6" style="display: none;">
            <h4 class="font-bold text-red-900 mb-2">Debug Information:</h4>
            <pre id="debugContent" class="text-sm text-red-700 whitespace-pre-wrap"></pre>
        </div>

        <!-- AI Report Sections -->
        <div id="aiReportSections" class="space-y-6" style="display: none;">
            
            <!-- Overview Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    📋 نظرة عامة سريعة
                </h3>
                <div id="overviewContent" class="text-gray-700 leading-relaxed">
                    <div class="animate-pulse flex space-x-4">
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-300 rounded w-1/2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4 Roots Analysis -->
            @foreach(['jawhar' => ['🎯', 'الجوهر'], 'zihn' => ['🧠', 'الذهن'], 'waslat' => ['🔗', 'الوصلات'], 'roaya' => ['👁️', 'الرؤية']] as $root => $data)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    {{ $data[0] }} تحليل {{ $data[1] }} - المعدل: {{ $rootsPerformance[$root]['overall'] ?? 0 }}%
                </h3>
                <div id="{{ $root }}AnalysisContent" class="text-gray-700 leading-relaxed">
                    <div class="animate-pulse flex space-x-4">
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 bg-gray-300 rounded w-full"></div>
                            <div class="h-4 bg-gray-300 rounded w-5/6"></div>
                            <div class="h-4 bg-gray-300 rounded w-4/6"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Group Tips -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    👥 نصائح للتعامل مع هذه المجموعة
                </h3>
                <div id="groupTipsContent" class="text-gray-700 leading-relaxed">
                    <div class="animate-pulse flex space-x-4">
                        <div class="flex-1 space-y-2 py-1">
                            <div class="h-4 bg-gray-300 rounded w-full"></div>
                            <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Plans -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Immediate Actions -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        ⚡ إجراءات فورية (الأسبوع القادم)
                    </h3>
                    <div id="immediateActionsContent" class="text-gray-700 leading-relaxed">
                        <div class="animate-pulse flex space-x-4">
                            <div class="flex-1 space-y-2 py-1">
                                <div class="h-4 bg-gray-300 rounded w-full"></div>
                                <div class="h-4 bg-gray-300 rounded w-5/6"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Long-term Strategies -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        🎯 استراتيجيات طويلة المدى
                    </h3>
                    <div id="longtermStrategiesContent" class="text-gray-700 leading-relaxed">
                        <div class="animate-pulse flex space-x-4">
                            <div class="flex-1 space-y-2 py-1">
                                <div class="h-4 bg-gray-300 rounded w-full"></div>
                                <div class="h-4 bg-gray-300 rounded w-4/6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts and Highlights -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Educational Alerts -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        ⚠️ تنبيهات تربوية
                    </h3>
                    <div id="educationalAlertsContent" class="text-gray-700 leading-relaxed">
                        <div class="animate-pulse flex space-x-4">
                            <div class="flex-1 space-y-2 py-1">
                                <div class="h-4 bg-gray-300 rounded w-full"></div>
                                <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bright Spots -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        ⭐ نقاط مضيئة
                    </h3>
                    <div id="brightSpotsContent" class="text-gray-700 leading-relaxed">
                        <div class="animate-pulse flex space-x-4">
                            <div class="flex-1 space-y-2 py-1">
                                <div class="h-4 bg-gray-300 rounded w-full"></div>
                                <div class="h-4 bg-gray-300 rounded w-5/6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-4 mt-8">
                <button onclick="window.print()" class="bg-gray-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-700 transition-all duration-300 shadow-lg">
                    🖨️ طباعة التقرير
                </button>
                <a href="{{ route('results.quiz', $quiz->id) }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all duration-300 shadow-lg">
                    📊 العودة للنتائج
                </a>
            </div>
        </div>

        <!-- No AI Access Message -->
        @if(!Auth::user()->canUseAI())
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-8 text-center">
            <div class="text-6xl mb-4">🤖</div>
            <h3 class="text-2xl font-bold text-orange-900 mb-4">التقارير التربوية الذكية</h3>
            <p class="text-orange-700 mb-6 max-w-2xl mx-auto">
                احصل على تحليل عميق لأداء طلابك باستخدام الذكاء الاصطناعي. يقدم التقرير رؤى تربوية قيمة وتوصيات عملية لتحسين التعليم.
            </p>
            <a href="{{ route('subscription.upgrade') }}" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-8 py-4 rounded-xl font-bold hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-lg text-lg">
                ⭐ اشترك الآن واحصل على التقارير الذكية
            </a>
        </div>
        @endif
    </div>
</div>

<!-- JavaScript for AI Report Generation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 AI Report page loaded');
    
    // Check if we have existing report data and display it immediately
    @if($hasExistingReport && $aiReportData)
        console.log('📊 Loading existing report data...');
        // Show AI sections immediately
        document.getElementById('aiReportSections').style.display = 'block';
        
        try {
            // Fill in the existing AI content
            const reportData = @json($aiReportData);
            console.log('📋 Report data:', reportData);
            
            if (reportData && typeof reportData === 'object') {
                fillReportContent(reportData);
            } else {
                console.error('❌ Invalid report data structure');
            }
        } catch (error) {
            console.error('❌ Error loading existing report data:', error);
        }
    @else
        console.log('🆕 No existing report found');
    @endif

    // Report Navigation Handler
    const reportSelector = document.getElementById('reportSelector');
    if (reportSelector) {
        reportSelector.addEventListener('change', function() {
            const selectedIndex = this.value;
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('report_index', selectedIndex);
            
            console.log('🔄 Navigating to report index:', selectedIndex);
            window.location.href = currentUrl.toString();
        });
    }

    // Keyboard Navigation (Optional Enhancement)
    document.addEventListener('keydown', function(event) {
        // Only handle navigation if we're not in an input field
        if (event.target.tagName !== 'INPUT' && event.target.tagName !== 'TEXTAREA') {
            @if($hasExistingReport && $reportNavigation['total_reports'] > 1)
                if (event.key === 'ArrowLeft' && {{ $reportNavigation['has_next'] ? 'true' : 'false' }}) {
                    // Arabic RTL: Left arrow goes to next report
                    window.location.href = "{{ route('results.ai-report', ['quiz' => $quiz->id, 'report_index' => $reportNavigation['next_index'] ?? 0]) }}";
                }
                if (event.key === 'ArrowRight' && {{ $reportNavigation['has_previous'] ? 'true' : 'false' }}) {
                    // Arabic RTL: Right arrow goes to previous report
                    window.location.href = "{{ route('results.ai-report', ['quiz' => $quiz->id, 'report_index' => $reportNavigation['previous_index'] ?? 0]) }}";
                }
            @endif
        }
    });

    // Generate Report Button Handler
    const generateBtn = document.getElementById('generateReportBtn');
    if (generateBtn) {
        generateBtn.addEventListener('click', function() {
            console.log('🔄 Generate button clicked');
            const button = this;
            const originalText = button.innerHTML;
            const isUpdate = originalText.includes('تحديث');
            
            // Confirm if it's an update (costs tokens)
            if (isUpdate) {
                if (!confirm('سيتم استخدام رصيد من الحصة الشهرية لتحديث التقرير. هل تريد المتابعة؟')) {
                    console.log('🚫 User cancelled update');
                    return;
                }
            }
            
            // Show loading state
            button.innerHTML = '🔄 جاري الإنشاء...';
            button.disabled = true;
            
            // Show AI sections with loading
            document.getElementById('aiReportSections').style.display = 'block';
            
            // Show loading placeholders
            showLoadingPlaceholders();
            
            console.log('🚀 Making AJAX request to generate report...');
            
            // Make AJAX request
            fetch(`{{ route('results.generate-ai-report', $quiz->id) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                console.log('📡 Response received, status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('✅ Response data received:', data);
                
                // Show debug info
                showDebugInfo(data);
                
                if (data && data.success) {
                    console.log('🎉 Report generated successfully');
                    
                    // Check if report_data exists and has the expected structure
                    if (data.report_data && typeof data.report_data === 'object') {
                        fillReportContent(data.report_data);
                    } else {
                        console.error('❌ Invalid report_data structure:', data.report_data);
                        throw new Error('البيانات المستلمة من الخادم غير صحيحة');
                    }
                    
                    // Update quota count
                    const quotaElement = document.getElementById('quotaCount');
                    if (quotaElement && data.remaining_quota !== undefined) {
                        quotaElement.textContent = data.remaining_quota;
                    }
                    
                    // Update button to show it's completed
                    button.innerHTML = '✅ تم ' + (isUpdate ? 'تحديث' : 'إنشاء') + ' التقرير';
                    button.style.background = 'linear-gradient(to right, #10b981, #059669)';
                    
                    // Reload page after 3 seconds to show new status
                    setTimeout(() => {
                        console.log('🔄 Reloading page to show updated status...');
                        window.location.reload();
                    }, 3000);
                    
                } else {
                    console.error('❌ Report generation failed:', data);
                    const errorMessage = data?.message || 'فشل في إنشاء التقرير';
                    throw new Error(errorMessage);
                }
            })
            .catch(error => {
                console.error('💥 Error occurred:', error);
                
                // Show debug info
                showDebugInfo({ error: error.message, stack: error.stack });
                
                // Show user-friendly error
                const errorMessage = error.message || 'حدث خطأ غير متوقع';
                alert('خطأ: ' + errorMessage);
                
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    } else {
        console.log('⚠️ Generate button not found on page');
    }
});

function fillReportContent(reportData) {
    console.log('📝 Filling report content with data:', reportData);
    
    const contentMap = {
        'overviewContent': reportData.overview || 'لم يتم إنشاء محتوى النظرة العامة',
        'jawharAnalysisContent': reportData.jawhar_analysis || 'لم يتم إنشاء تحليل الجوهر',
        'zihnAnalysisContent': reportData.zihn_analysis || 'لم يتم إنشاء تحليل الذهن',
        'waslatAnalysisContent': reportData.waslat_analysis || 'لم يتم إنشاء تحليل الوصلات',
        'roayaAnalysisContent': reportData.roaya_analysis || 'لم يتم إنشاء تحليل الرؤية',
        'groupTipsContent': reportData.group_tips || 'لم يتم إنشاء نصائح المجموعة',
        'immediateActionsContent': reportData.immediate_actions || 'لم يتم إنشاء الإجراءات الفورية',
        'longtermStrategiesContent': reportData.longterm_strategies || 'لم يتم إنشاء الاستراتيجيات طويلة المدى',
        'educationalAlertsContent': reportData.educational_alerts || 'لم يتم إنشاء التنبيهات التربوية',
        'brightSpotsContent': reportData.bright_spots || 'لم يتم إنشاء النقاط المضيئة'
    };
    
    let successCount = 0;
    for (const [elementId, content] of Object.entries(contentMap)) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = content;
            successCount++;
        } else {
            console.warn(`⚠️ Element not found: ${elementId}`);
        }
    }
    
    console.log(`✅ Successfully filled ${successCount}/${Object.keys(contentMap).length} content sections`);
}

function showLoadingPlaceholders() {
    console.log('⏳ Showing loading placeholders...');
    
    const placeholderHTML = `
        <div class="animate-pulse flex space-x-4">
            <div class="flex-1 space-y-2 py-1">
                <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                <div class="h-4 bg-gray-300 rounded w-1/2"></div>
            </div>
        </div>
    `;
    
    const contentIds = [
        'overviewContent', 'jawharAnalysisContent', 'zihnAnalysisContent', 
        'waslatAnalysisContent', 'roayaAnalysisContent', 'groupTipsContent',
        'immediateActionsContent', 'longtermStrategiesContent', 
        'educationalAlertsContent', 'brightSpotsContent'
    ];
    
    contentIds.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.innerHTML = placeholderHTML;
        }
    });
}

function showDebugInfo(data) {
    const debugSection = document.getElementById('debugInfo');
    const debugContent = document.getElementById('debugContent');
    
    if (debugSection && debugContent) {
        debugContent.textContent = JSON.stringify(data, null, 2);
        debugSection.style.display = 'block';
        
        // Auto-hide after 10 seconds
        setTimeout(() => {
            debugSection.style.display = 'none';
        }, 10000);
    }
}
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .bg-gradient-to-br,
    .backdrop-blur-xl {
        background: white !important;
    }
    
    .shadow-xl,
    .shadow-lg {
        box-shadow: none !important;
    }
    
    #debugInfo {
        display: none !important;
    }
}
</style>
@endsection