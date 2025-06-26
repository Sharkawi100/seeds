# جُذور Platform - Complete File Index

**Last Updated**: June 26, 2025  
**Version**: 2.1 with Advanced Subscription Cancellation System

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

#### Subscription Controllers (UPDATED)

-   **SubscriptionController.php** - User subscription management with cancellation
-   **ProfileController.php** - User profile with subscription status

#### Admin Controllers

-   **Admin/UserController.php** - User management
-   **Admin/QuizController.php** - Quiz administration
-   **Admin/SubscriptionPlanController.php** - Subscription plan management
-   **Admin/ContactController.php** - UPDATED: Contact messages with cancellation tracking

### app/Models/

#### Core Models

-   **User.php** - User model with subscription methods (UPDATED)
-   **Quiz.php** - Quiz model with 4-roots logic
-   **Question.php** - Question model with root classification
-   **Result.php** - Result model with attempt tracking
-   **Answer.php** - Individual answer records
-   **Subject.php** - Subject/course management

#### Subscription Models (UPDATED)

-   **Subscription.php** - User subscription records with cancellation tracking
-   **SubscriptionPlan.php** - Available subscription plans
-   **MonthlyQuota.php** - Monthly usage tracking

#### Communication Models (UPDATED)

-   **ContactMessage.php** - UPDATED: Contact messages with subscription relationship

### app/Http/Middleware/

#### Core Middleware

-   **Authenticate.php** - Authentication middleware
-   **SetLocale.php** - Language switching middleware
-   **IsAdmin.php** - Admin access control

#### Subscription Middleware

-   **RequireSubscription.php** - AI feature access control

### app/Services/

#### Core Services

-   **ClaudeService.php** - AI content generation (UPDATED: subscription integration)

#### Payment Services (UPDATED)

-   **LemonSqueezyService.php** - UPDATED: Payment processing, webhooks, and cancellation handling

## 🗄️ Database Structure

### database/migrations/

#### Core Tables

-   **2014_10_12_000000_create_users_table.php** - Users with subscription fields
-   **create_quizzes_table.php** - Quiz storage
-   **create_questions_table.php** - Question storage with 4-roots
-   **create_results_table.php** - Results with attempt tracking
-   **create_subjects_table.php** - Subject management

#### Subscription Tables (UPDATED)

-   **create_subscription_plans_table.php** - Available plans
-   **create_subscriptions_table.php** - UPDATED: User subscriptions with cancellation tracking
-   **create_monthly_quotas_table.php** - Usage tracking
-   **add_subscription_fields_to_users_table.php** - User subscription status

#### Contact System Tables (UPDATED)

-   **create_contact_categories_table.php** - Contact categories
-   **create_contact_messages_table.php** - UPDATED: Contact messages with subscription reference

### database/seeders/

-   **DatabaseSeeder.php** - Main seeder
-   **SubjectSeeder.php** - Default subjects (Arabic, English, Hebrew)
-   **SubscriptionPlanSeeder.php** - Default subscription plans
-   **ContactCategorySeeder.php** - UPDATED: Contact categories including cancellation

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

#### Subscription Views (UPDATED)

-   **subscription/upgrade.blade.php** - Subscription plans and checkout
-   **subscription/success.blade.php** - Payment success page
-   **subscription/manage.blade.php** - UPDATED: Subscription management with cancellation system

#### Profile Management

-   **profile/index.blade.php** - User profile with subscription status
-   **profile/edit.blade.php** - Profile editing

#### Admin Interface

-   **admin/dashboard.blade.php** - Admin dashboard
-   **admin/users/index.blade.php** - User management (UPDATED: subscription icons)
-   **admin/users/show.blade.php** - User details with subscription management
-   **admin/users/edit.blade.php** - User editing

#### Admin Subscription Management

-   **admin/subscription-plans/index.blade.php** - Plan management
-   **admin/subscription-plans/create.blade.php** - Create subscription plan
-   **admin/subscription-plans/edit.blade.php** - Edit subscription plan
-   **admin/subscription-plans/show.blade.php** - Plan details and subscribers
-   **admin/subscription-plans/users.blade.php** - Subscription user management
-   **admin/subscription-plans/manage-user.blade.php** - Individual user subscription management

#### NEW: Admin Contact Management (UPDATED)

-   **admin/contact/index.blade.php** - UPDATED: Modern contact interface with cancellation highlighting
-   **admin/contact/show.blade.php** - Individual contact message details

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

// Subscription routes (UPDATED)
Route::get('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
Route::post('/subscription/checkout', [SubscriptionController::class, 'createCheckout'])->name('subscription.checkout');
Route::get('/subscription/manage', [SubscriptionController::class, 'manage'])->name('subscription.manage');
Route::post('/subscription/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel'); // NEW
```

#### AI Features (Subscription Required)

```php
Route::middleware(['auth', 'subscription'])->group(function () {
    Route::post('/quizzes/{quiz}/generate-text', [QuizController::class, 'generateText'])->name('quizzes.generate-text');
    Route::post('/quizzes/{quiz}/generate-questions', [QuizController::class, 'generateQuestions'])->name('quizzes.generate-questions');
});
```

#### Admin Routes (UPDATED)

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // User management
    Route::resource('users', Admin\UserController::class);

    // Subscription management
    Route::resource('subscription-plans', Admin\SubscriptionPlanController::class);
    Route::get('subscription-plans-users', [Admin\SubscriptionPlanController::class, 'users'])->name('subscription-plans.users');
    Route::get('users/{user}/manage-subscription', [Admin\SubscriptionPlanController::class, 'manageUserSubscription'])->name('users.manage-subscription');

    // Contact management (UPDATED)
    Route::get('contact', [Admin\ContactController::class, 'index'])->name('contact.index');
    Route::patch('contact/{message}/mark-read', [Admin\ContactController::class, 'markAsRead'])->name('contact.mark-read');
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

## 📚 Documentation Files (UPDATED)

### \_project_docs/

-   **schema_summary.md** - Complete database and route documentation (UPDATED)
-   **code_patterns.md** - Development patterns and conventions (UPDATED)
-   **features_and_logic.md** - Feature specifications (UPDATED)
-   **bug_fixes_log.md** - Historical bug fixes and solutions
-   **subscription_system.md** - UPDATED: Comprehensive subscription documentation with cancellation
-   **cancellation_system.md** - NEW: Detailed cancellation system documentation
-   **file_index.md** - This file (UPDATED)

## 🔧 Key File Relationships (UPDATED)

### Subscription Cancellation System Integration

```
User Model (subscription methods)
    ├── SubscriptionController (user management + cancellation)
    ├── Admin/SubscriptionPlanController (admin management)
    ├── LemonSqueezyService (payment processing + cancellation)
    ├── RequireSubscription middleware (feature gating)
    ├── Subscription views (user interface + cancellation UI)
    └── ContactMessage model (cancellation feedback)

ContactMessage System Integration
    ├── ContactController (admin contact management)
    ├── ContactMessage model (with subscription relationship)
    ├── Admin contact views (modern interface)
    └── Cancellation category integration

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

### Admin Interface (UPDATED)

```
Admin Controllers
    ├── User management with subscription icons
    ├── Subscription plan CRUD
    ├── User subscription management
    ├── Contact message management (UPDATED: with cancellation highlighting)
    └── Cancellation tracking and retention tools
```

## 📊 File Statistics (UPDATED)

### Core Application

-   **PHP Files**: 48+ controllers, models, services, middleware (3 new files)
-   **Blade Templates**: 62+ views covering all features (2 updated views)
-   **Database Files**: 17+ migrations, 6+ seeders (2 updated migrations)
-   **Documentation**: 12+ comprehensive documentation files (3 updated files)

### Subscription System (UPDATED)

-   **Backend Files**: 10+ controllers, models, services (2 updated files)
-   **Frontend Files**: 14+ views and components (2 updated views)
-   **Database Files**: 6+ migrations and seeders (2 updated migrations)
-   **Documentation**: 5+ dedicated documentation files (2 updated files)

### NEW: Cancellation System Features

-   **Retention Interface**: Modern pros/cons comparison system
-   **Contact Integration**: Centralized cancellation feedback system
-   **Admin Tools**: Enhanced contact management with cancellation focus
-   **Business Intelligence**: Cancellation reason tracking and analysis

## 🚀 Deployment Structure

### Production Environment

```
/home/jqfujdmy/roots_app/          # Application files
    ├── All Laravel files
    ├── Environment configuration
    ├── Updated subscription system
    ├── New cancellation features
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
    ├── Updated subscription system
    ├── New cancellation features
    └── Development database
```

---

## 📈 Recent Additions (June 26, 2025)

### Major Feature Additions

1. **Advanced Cancellation System**: Complete subscription cancellation with retention
2. **Contact System Integration**: Centralized feedback system for cancellations
3. **Modern Admin Interface**: Enhanced contact management with cancellation focus
4. **Retention Tools**: Professional retention campaigns and follow-up systems

### File Modifications

1. **Subscription Management**: Enhanced with inline cancellation interface
2. **Contact System**: Updated with subscription relationship and modern UI
3. **Admin Interface**: Enhanced with cancellation tracking and retention tools
4. **Database Schema**: Updated with cancellation tracking and contact integration

### Business Intelligence Features

1. **Cancellation Tracking**: Detailed reason collection and analysis
2. **Retention Metrics**: Track retention efforts and success rates
3. **Admin Dashboard**: Enhanced with cancellation insights
4. **Follow-up System**: Automated retention campaign tools

**Total Project Size**: 105+ files across backend, frontend, database, and documentation  
**Code Quality**: Production-ready with comprehensive error handling and security measures  
**Documentation**: Complete technical and user documentation in Arabic and English  
**Business Features**: Advanced subscription management with retention focus

---

## 🔮 Future Enhancements

### Planned Features

-   **Automated Retention Campaigns**: Email sequences for cancelled users
-   **Advanced Analytics**: Detailed cancellation reason analysis
-   **Pause Subscription**: Alternative to cancellation
-   **Win-back Campaigns**: Re-engagement for expired users

### Technical Improvements

-   **API Rate Limiting**: Enhanced for cancelled users
-   **Advanced Notifications**: Real-time cancellation alerts
-   **Mobile App Integration**: Cancellation management on mobile
-   **Performance Optimization**: Enhanced for retention workflows

---

**Implementation Status**: ✅ Complete and Production Ready  
**Last Major Update**: June 26, 2025 - Advanced Cancellation System  
**Next Review**: September 2025
