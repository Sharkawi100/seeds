// Admin Dashboard JavaScript Functions
// Add this to your resources/js folder and include it in your build process

window.dashboardHelpers = {
    // Base URL for API calls
    apiBase: "/admin/api",

    // Fetch with CSRF token
    async fetchApi(url, options = {}) {
        const defaultOptions = {
            headers: {
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            ...options,
        };

        try {
            const response = await fetch(this.apiBase + url, defaultOptions);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || "Request failed");
            }

            return data;
        } catch (error) {
            console.error("API Error:", error);
            throw error;
        }
    },

    // Load metrics data
    async loadMetrics(dateRange = "week") {
        return await this.fetchApi(`/metrics?dateRange=${dateRange}`);
    },

    // Load chart data
    async loadChartData(chartType, dateRange = "week") {
        return await this.fetchApi(
            `/charts/${chartType}?dateRange=${dateRange}`
        );
    },

    // Get teacher statistics
    async getTeacherStats(teacherId) {
        return await this.fetchApi(`/teachers/${teacherId}/stats`);
    },

    // Get student progress
    async getStudentProgress(studentId) {
        return await this.fetchApi(`/students/${studentId}/progress`);
    },

    // Get recent activities
    async getRecentActivities(limit = 20) {
        return await this.fetchApi(`/recent-activities?limit=${limit}`);
    },

    // Get top teachers
    async getTopTeachers(dateRange = "month") {
        return await this.fetchApi(`/top-teachers?dateRange=${dateRange}`);
    },

    // Get student progress list
    async getStudentProgressList(dateRange = "month") {
        return await this.fetchApi(`/student-progress?dateRange=${dateRange}`);
    },

    // Get quiz statistics
    async getQuizStats(dateRange = "month") {
        return await this.fetchApi(`/quiz-stats?dateRange=${dateRange}`);
    },

    // Get filtered reports data
    async getReportsData(filters = {}) {
        const queryString = new URLSearchParams(filters).toString();
        return await this.fetchApi(`/reports-data?${queryString}`);
    },

    // Generate AI insights
    async generateAIInsights() {
        return await this.fetchApi("/generate-insights", {
            method: "POST",
        });
    },

    // Update chart instance
    updateChart(chart, data) {
        if (!chart) return;

        if (data.labels) {
            chart.data.labels = data.labels;
        }

        if (data.datasets) {
            chart.data.datasets = data.datasets;
        } else if (data.data && chart.data.datasets[0]) {
            chart.data.datasets[0].data = data.data;
        }

        chart.update();
    },

    // Format number for Arabic display
    formatNumber(num) {
        return new Intl.NumberFormat("ar-SA").format(num);
    },

    // Format percentage
    formatPercent(num) {
        return new Intl.NumberFormat("ar-SA", {
            style: "percent",
            maximumFractionDigits: 0,
        }).format(num / 100);
    },

    // Show loading state
    showLoading(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `
                <div class="flex items-center justify-center p-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-white border-t-transparent"></div>
                </div>
            `;
        }
    },

    // Show error message
    showError(elementId, message = "حدث خطأ في تحميل البيانات") {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `
                <div class="bg-red-500/20 border border-red-500/40 rounded-lg p-4 text-red-300">
                    <i class="fas fa-exclamation-triangle ml-2"></i>
                    ${message}
                </div>
            `;
        }
    },

    // Initialize tooltips
    initTooltips() {
        // Initialize any tooltip library you're using
    },

    // Export data to CSV
    exportToCSV(data, filename = "report.csv") {
        const csv = this.convertToCSV(data);
        const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
        const link = document.createElement("a");

        if (navigator.msSaveBlob) {
            navigator.msSaveBlob(blob, filename);
        } else {
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
        }
    },

    // Convert data to CSV format
    convertToCSV(data) {
        if (!data || data.length === 0) return "";

        const headers = Object.keys(data[0]).join(",");
        const rows = data.map((row) => Object.values(row).join(","));

        return [headers, ...rows].join("\n");
    },

    // Debounce function for search/filter inputs
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Parse date range
    getDateRangeParams(range) {
        const now = new Date();
        let startDate,
            endDate = now;

        switch (range) {
            case "today":
                startDate = new Date(now.setHours(0, 0, 0, 0));
                break;
            case "week":
                startDate = new Date(now.setDate(now.getDate() - 7));
                break;
            case "month":
                startDate = new Date(now.setMonth(now.getMonth() - 1));
                break;
            case "quarter":
                startDate = new Date(now.setMonth(now.getMonth() - 3));
                break;
            case "year":
                startDate = new Date(now.getFullYear(), 0, 1);
                break;
            default:
                startDate = new Date(now.setDate(now.getDate() - 7));
        }

        return {
            start: startDate.toISOString().split("T")[0],
            end: endDate.toISOString().split("T")[0],
        };
    },
};

// Alpine.js data functions enhancement
window.dashboardData = function () {
    return {
        // ... existing data properties ...

        async init() {
            this.loadData();
            this.initCharts();

            // Auto refresh every 30 seconds
            setInterval(() => {
                this.refreshData();
            }, 30000);
        },

        async loadData() {
            try {
                // Load metrics
                const metrics = await window.dashboardHelpers.loadMetrics(
                    this.dateRange
                );
                this.stats = metrics;

                // Load teacher data
                const teacherData =
                    await window.dashboardHelpers.getTopTeachers(
                        this.dateRange
                    );
                this.topTeachers = teacherData.teachers;

                // Load student progress
                const studentData =
                    await window.dashboardHelpers.getStudentProgressList(
                        this.dateRange
                    );
                this.studentProgress = studentData.students;

                // Load quiz stats
                const quizData = await window.dashboardHelpers.getQuizStats(
                    this.dateRange
                );
                this.quizStats = quizData.stats;
                this.popularQuizzes = quizData.popularQuizzes;
                this.questionTypes = quizData.questionTypes;

                // Load recent activities
                const activities =
                    await window.dashboardHelpers.getRecentActivities();
                this.recentActivities = activities.activities;

                // Update charts
                await this.updateCharts();
            } catch (error) {
                console.error("Failed to load dashboard data:", error);
            }
        },

        async updateCharts() {
            // Update user growth chart
            const userGrowthData = await window.dashboardHelpers.loadChartData(
                "user-growth",
                this.dateRange
            );
            window.dashboardHelpers.updateChart(
                this.charts.userGrowth,
                userGrowthData
            );

            // Update root performance chart
            const rootPerfData = await window.dashboardHelpers.loadChartData(
                "root-performance",
                this.dateRange
            );
            window.dashboardHelpers.updateChart(
                this.charts.rootPerformance,
                rootPerfData
            );

            // Update other charts based on active tab
            if (this.activeTab === "students") {
                const gradeDistData =
                    await window.dashboardHelpers.loadChartData(
                        "grade-distribution",
                        this.dateRange
                    );
                window.dashboardHelpers.updateChart(
                    this.charts.gradeDistribution,
                    gradeDistData
                );

                const subjectPerfData =
                    await window.dashboardHelpers.loadChartData(
                        "subject-performance",
                        this.dateRange
                    );
                window.dashboardHelpers.updateChart(
                    this.charts.subjectPerformance,
                    subjectPerfData
                );
            }
        },

        async refreshData() {
            this.loading = true;
            await this.loadData();
            this.lastUpdate = new Date().toLocaleTimeString("ar-SA");
            this.loading = false;
        },

        // Teacher detail modal
        async showTeacherDetails(teacherId) {
            try {
                const data = await window.dashboardHelpers.getTeacherStats(
                    teacherId
                );
                // Show modal with teacher data
                this.selectedTeacher = data.teacher;
                this.teacherStats = data.stats;
                this.teacherRootPerformance = data.rootPerformance;
                this.showTeacherModal = true;
            } catch (error) {
                console.error("Failed to load teacher details:", error);
            }
        },

        // Student detail modal
        async showStudentDetails(studentId) {
            try {
                const data = await window.dashboardHelpers.getStudentProgress(
                    studentId
                );
                // Show modal with student data
                this.selectedStudent = data.student;
                this.studentStats = data.stats;
                this.studentRootProgress = data.rootProgress;
                this.showStudentModal = true;
            } catch (error) {
                console.error("Failed to load student details:", error);
            }
        },
    };
};

// Reports page data functions enhancement
window.reportsData = function () {
    return {
        // ... existing data properties ...

        async init() {
            await this.loadData();
            this.initCharts();
        },

        async loadData() {
            try {
                const filters = {
                    dateRange: this.filters.dateRange,
                    subject: this.filters.subject,
                    grade: this.filters.grade,
                    userType: this.filters.userType,
                };

                const data = await window.dashboardHelpers.getReportsData(
                    filters
                );

                // Update all data properties
                this.summary = data.summary;
                this.rootAnalysis = data.rootAnalysis;
                this.performanceHeatmap = data.performanceHeatmap;
                this.timeAnalysis = data.timeAnalysis;
                this.topPerformers = data.topPerformers;
                this.questionDifficultyData = data.questionDifficulty;
                this.schoolComparison = data.schoolComparison;

                // Update charts
                this.updateCharts();
            } catch (error) {
                console.error("Failed to load reports data:", error);
            }
        },

        async generateReport() {
            try {
                // Show loading state
                this.loading = true;

                // Generate AI report
                const insights =
                    await window.dashboardHelpers.generateAIInsights();
                this.aiInsights = insights.insights;

                // Could also trigger a full PDF report generation here
            } catch (error) {
                console.error("Failed to generate report:", error);
                alert("فشل في توليد التقرير. يرجى المحاولة مرة أخرى.");
            } finally {
                this.loading = false;
            }
        },

        async generateNewInsights() {
            try {
                const insights =
                    await window.dashboardHelpers.generateAIInsights();
                this.aiInsights = insights.insights;
            } catch (error) {
                console.error("Failed to generate insights:", error);
            }
        },

        // Apply filters with debouncing
        applyFilters: window.dashboardHelpers.debounce(async function () {
            await this.loadData();
        }, 500),

        // Export functionality
        exportData(type) {
            let data = [];
            let filename = "";

            switch (type) {
                case "performance":
                    data = this.schoolComparison;
                    filename = "school_performance.csv";
                    break;
                case "questions":
                    data = this.questionDifficultyData;
                    filename = "question_analysis.csv";
                    break;
                case "students":
                    data = this.topPerformers.filter(
                        (p) => p.type === "student"
                    );
                    filename = "top_students.csv";
                    break;
                default:
                    return;
            }

            window.dashboardHelpers.exportToCSV(data, filename);
        },
    };
};
