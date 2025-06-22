# جُذور Platform - Complete File Index

**Last Updated**: June 22, 2025  
**Version**: 2.0 with Subscription System

## 🎯 Core Configuration Files

### Environment & Dependencies

-   **.env** - Environment configuration with Lemon Squeezy credentials
-   **.env.example** - Environment template
-   **composer.json** - PHP dependencies including Laravel 11
-   **package.json** - Node.js dependencies (Tailwind CSS, Alpine.js)
-   **vite.config.js** - Asset compilation configuration

### Application Configuration

-   **config/app.php** - Application settings with Arabic locale
-   **config/auth.php** - Authentication configuration
-   **config/database.php** - Database configuration for MySQL
-   **config/services.php** - External services (Lemon Squeezy, Claude API)

## 📁 Application Structure

### app/Http/Controllers/

#### Core Controllers

-   **Controller.php** - Base controller with common methods
-   **QuizController.php** - Quiz CRUD and AI generation (UPDATED: subscription checks)
-   **QuestionController.php** - Question management
-   **ResultController.php** - Result processing and analytics

#### Authentication Controllers (Laravel Breeze)

-   **Auth/AuthenticatedSessionController.php** - Login/logout
-   **Auth/RegisteredUserController.php** - User registration
-   **Auth/PasswordResetLinkController.php** - Password reset

#### NEW: Subscription Controllers

-   **SubscriptionController.php** - User subscription management
-   **ProfileController.php** - User profile with subscription status

#### Admin Controllers

-   **Admin/UserController.php** - User management
-   **Admin/QuizController.php** - Quiz administration
-   **Admin/SubscriptionPlanController.php** - NEW: Subscription plan management

### app/Models/

#### Core Models

-   **User.php** - User model with subscription methods (UPDATED)
-   **Quiz.php** - Quiz model with 4-roots logic
-   **Question.php** - Question model with root classification
-   **Result.php** - Result model with attempt tracking
-   **Answer.php** - Individual answer records
-   **Subject.php** - Subject/course management

#### NEW: Subscription Models

-   **Subscription.php** - User subscription records
-   **SubscriptionPlan.php** - Available subscription plans
-   **MonthlyQuota.php** - Monthly usage tracking

### app/Http/Middleware/

#### Core Middleware

-   **Authenticate.php** - Authentication middleware
-   **SetLocale.php** - Language switching middleware
-   **IsAdmin.php** - Admin access control

#### NEW: Subscription Middleware

-   **RequireSubscription.php** - AI feature access control

### app/Services/

#### Core Services

-   **ClaudeService.php** - AI content generation (UPDATED: subscription integration)

#### NEW: Payment Services

-   **LemonSqueezyService.php** - Payment processing and webhooks

## 🗄️ Database Structure

### database/migrations/

#### Core Tables

-   **2014_10_12_000000_create_users_table.php** - Users with subscription fields
-   **create_quizzes_table.php** - Quiz storage
-   **create_questions_table.php** - Question storage with 4-roots
-   **create_results_table.php** - Results with attempt tracking
-   **create_subjects_table.php** - Subject management

#### NEW: Subscription Tables

-   **create_subscription_plans_table.php** - Available plans
-   **create_subscriptions_table.php** - User subscriptions
-   **create_monthly_quotas_table.php** - Usage tracking
-   **add_subscription_fields_to_users_table.php** - User subscription status

### database/seeders/

-   **DatabaseSeeder.php** - Main seeder
-   **SubjectSeeder.php** - Default subjects (Arabic, English, Hebrew)
-   **SubscriptionPlanSeeder.php** - NEW: Default subscription plans

## 🎨 Frontend Resources

### resources/views/

#### Layout & Components

-   **layouts/app.blade.php** - Main layout with subscription navigation
-   **layouts/guest.blade.php** - Guest layout
-   **components/** - Reusable Blade components

#### Authentication Views

-   **auth/login.blade.php** - Login form
-   **auth/register.blade.php** - Registration form
-   **auth/forgot-password.blade.php** - Password reset

#### Core Application Views

-   **dashboard.blade.php** - User dashboard with subscription widget
-   **welcome.blade.php** - Landing page with PIN entry

#### Quiz Management

-   **quizzes/index.blade.php** - Quiz listing
-   **quizzes/create.blade.php** - 3-step quiz creation wizard (UPDATED: subscription checks)
-   **quizzes/show.blade.php** - Quiz details and management
-   **quizzes/edit.blade.php** - Quiz editing
-   **quizzes/take.blade.php** - Quiz taking interface

#### Question Management

-   **quizzes/questions/create.blade.php** - Question creation
-   **quizzes/questions/edit.blade.php** - Question editing
-   **quizzes/questions/bulk-edit.blade.php** - Bulk question management

#### Results & Analytics

-   **results/index.blade.php** - Results listing
-   **results/show.blade.php** - Individual result with 4-roots chart
-   **results/quiz-results.blade.php** - Quiz-wide analytics

#### NEW: Subscription Views

-   **subscription/upgrade.blade.php** - Subscription plans and checkout
-   **subscription/success.blade.php** - Payment success page
-   **subscription/manage.blade.php** - Subscription management

#### NEW: Profile Management

-   **profile/index.blade.php** - User profile with subscription status
-   **profile/edit.blade.php** - Profile editing

#### Admin Interface

-   **admin/dashboard.blade.php** - Admin dashboard
-   **admin/users/index.blade.php** - User management (UPDATED: subscription icons)
-   **admin/users/show.blade.php** - User details with subscription management
-   **admin/users/edit.blade.php** - User editing

#### NEW: Admin Subscription Management

-   **admin/subscription-plans/index.blade.php** - Plan management
-   **admin/subscription-plans/create.blade.php** - Create subscription plan
-   **admin/subscription-plans/edit.blade.php** - Edit subscription plan
-   **admin/subscription-plans/show.blade.php** - Plan details and subscribers
-   **admin/subscription-plans/users.blade.php** - Subscription user management
-   **admin/subscription-plans/manage-user.blade.php** - Individual user subscription management

### resources/css/

-   **app.css** - Main stylesheet with RTL support

### resources/js/

-   **app.js** - Main JavaScript file
-   **bootstrap.js** - Framework initialization

## 🛣️ Routes

### routes/web.php - Complete Route Structure

#### Public Routes

```php
// Landing pages
Route::get('/', WelcomeController::class)->name('welcome');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Quiz taking (public)
Route::get('/quiz/{quiz:pin}', [QuizController::class, 'take'])->name('quiz.take');
Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
```

#### Authenticated Routes

```php
// Dashboard and profile
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/profile', [ProfileController::class, 'profileDashboard'])->name('profile.dashboard');

// Quiz management
Route::resource('quizzes', QuizController::class);
Route::post('/quizzes/create-step-1', [QuizController::class, 'createStep1'])->name('quizzes.create-step-1');

// NEW: Subscription routes
Route::get('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
Route::post('/subscription/checkout', [SubscriptionController::class, 'createCheckout'])->name('subscription.checkout');
Route::get('/subscription/manage', [SubscriptionController::class, 'manage'])->name('subscription.manage');
```

#### AI Features (Subscription Required)

```php
Route::middleware(['auth', 'subscription'])->group(function () {
    Route::post('/quizzes/{quiz}/generate-text', [QuizController::class, 'generateText'])->name('quizzes.generate-text');
    Route::post('/quizzes/{quiz}/generate-questions', [QuizController::class, 'generateQuestions'])->name('quizzes.generate-questions');
});
```

#### Admin Routes

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // User management
    Route::resource('users', Admin\UserController::class);

    // NEW: Subscription management
    Route::resource('subscription-plans', Admin\SubscriptionPlanController::class);
    Route::get('subscription-plans-users', [Admin\SubscriptionPlanController::class, 'users'])->name('subscription-plans.users');
    Route::get('users/{user}/manage-subscription', [Admin\SubscriptionPlanController::class, 'manageUserSubscription'])->name('users.manage-subscription');
});
```

#### Webhooks

```php
Route::post('/webhooks/lemonsqueezy', [SubscriptionController::class, 'webhook'])->name('subscription.webhook');
```

## ⚙️ Bootstrap & Configuration

### bootstrap/app.php

-   Application bootstrap with subscription middleware registration

### public/

-   **index.php** - Application entry point
-   **build/** - Compiled assets (CSS, JS)
-   **storage/app/public** - Public file storage

## 📚 Documentation Files

### \_project_docs/

-   **schema_summary.md** - Complete database and route documentation (UPDATED)
-   **code_patterns.md** - Development patterns and conventions (UPDATED)
-   **features_and_logic.md** - Feature specifications (UPDATED)
-   **bug_fixes_log.md** - Historical bug fixes and solutions
-   **subscription_system.md** - NEW: Comprehensive subscription documentation
-   **file_index.md** - This file

## 🔧 Key File Relationships

### Subscription System Integration

```
User Model (subscription methods)
    ├── SubscriptionController (user management)
    ├── Admin/SubscriptionPlanController (admin management)
    ├── LemonSqueezyService (payment processing)
    ├── RequireSubscription middleware (feature gating)
    └── Subscription views (user interface)

QuizController (AI features)
    ├── ClaudeService (AI integration)
    ├── MonthlyQuota tracking
    └── Subscription validation
```

### Authentication Flow

```
Laravel Breeze Controllers
    ├── User registration/login
    ├── Password reset
    └── Profile management

Custom Extensions
    ├── Google OAuth
    ├── Subscription status
    └── Arabic locale support
```

### Admin Interface

```
Admin Controllers
    ├── User management with subscription icons
    ├── Subscription plan CRUD
    ├── User subscription management
    └── System analytics
```

## 📊 File Statistics

### Core Application

-   **PHP Files**: 45+ controllers, models, services, middleware
-   **Blade Templates**: 60+ views covering all features
-   **Database Files**: 15+ migrations, 5+ seeders
-   **Documentation**: 10+ comprehensive documentation files

### NEW: Subscription System

-   **Backend Files**: 8+ new controllers, models, services
-   **Frontend Files**: 12+ new views and components
-   **Database Files**: 4+ new migrations and seeders
-   **Documentation**: 3+ dedicated documentation files

## 🚀 Deployment Structure

### Production Environment

```
/home/jqfujdmy/roots_app/          # Application files
    ├── All Laravel files
    ├── Environment configuration
    └── Storage and logs

/home/jqfujdmy/public_html/roots/  # Web-accessible
    ├── index.php (entry point)
    └── Compiled assets
```

### Development Environment

```
local/roots/
    ├── Complete Laravel structure
    ├── Node modules and build tools
    └── Development database
```

---

## 📈 Recent Additions (June 2025)

### Major File Additions

1. **Subscription System**: 20+ new files for complete payment integration
2. **Admin Enhancements**: 8+ new views for subscription management
3. **User Profile**: Enhanced profile management with subscription status
4. **Documentation**: Comprehensive documentation updates

### File Modifications

1. **Quiz Creation**: Enhanced with subscription checks and AI gating
2. **User Management**: Added subscription status and management
3. **Dashboard**: Integrated subscription widgets and usage tracking
4. **Navigation**: Added subscription management links

**Total Project Size**: 100+ files across backend, frontend, database, and documentation  
**Code Quality**: Production-ready with comprehensive error handling and security measures  
**Documentation**: Complete technical and user documentation in Arabic and English
