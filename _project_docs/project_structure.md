# Project Structure Summary - جُذور (Juzoor)

Last Updated: June 2025

## 🏛️ Architecture Overview

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── AuthenticatedSessionController.php
│   │   │   ├── ConfirmablePasswordController.php
│   │   │   ├── EmailVerificationNotificationController.php
│   │   │   ├── EmailVerificationPromptController.php
│   │   │   ├── NewPasswordController.php
│   │   │   ├── PasswordController.php
│   │   │   ├── PasswordResetLinkController.php
│   │   │   ├── RegisteredUserController.php
│   │   │   └── VerifyEmailController.php
│   │   ├── Admin/
│   │   │   ├── AiManagementController.php (AI quiz generation)
│   │   │   ├── DashboardController.php
│   │   │   ├── QuizController.php
│   │   │   └── UserController.php
│   │   ├── Controller.php (base)
│   │   ├── ContactController.php
│   │   ├── ProfileController.php
│   │   ├── QuestionController.php
│   │   ├── QuizController.php (main quiz management)
│   │   ├── ResultController.php
│   │   └── WelcomeController.php (landing page)
│   ├── Middleware/
│   │   ├── IsAdmin.php (custom admin check)
│   │   └── SetLocale.php (language switching)
│   └── Requests/
│       ├── Auth/
│       │   └── LoginRequest.php
│       └── ProfileUpdateRequest.php
├── Models/
│   ├── Answer.php
│   ├── Question.php
│   ├── Quiz.php
│   ├── Result.php
│   └── User.php
├── Policies/
│   └── QuizPolicy.php
├── Services/
│   └── ClaudeService.php (AI integration)
├── Mail/
│   └── ContactInquiry.php
└── View/
    └── Components/
        ├── AppLayout.php
        └── GuestLayout.php
```

## 🔄 Request Flow for Quiz System

1. **Guest Access**: PIN entry → Quiz taking → Results (with guest token)
2. **User Flow**: Login → Dashboard → Create/Manage Quizzes → View Analytics
3. **Admin Flow**: All user capabilities + User management + AI settings

## 🎯 Key Models & Relationships

### Core Models

```
User
├── hasMany → Quiz (teacher creates quizzes)
├── hasMany → Result (student results)
└── boolean: is_admin, is_school

Quiz
├── belongsTo → User (creator)
├── hasMany → Question
├── hasMany → Result
├── attributes: title, subject, grade_level, settings
├── features: PIN access, AI generation
└── subjects: arabic, english, hebrew

Question
├── belongsTo → Quiz
├── hasMany → Answer
├── attributes: question, options[], correct_answer
├── root_type: jawhar|zihn|waslat|roaya
└── depth_level: 1|2|3

Result
├── belongsTo → Quiz
├── belongsTo → User (nullable for guests)
├── hasMany → Answer
├── scores: {jawhar: %, zihn: %, waslat: %, roaya: %}
└── guest_token: for non-authenticated access

Answer
├── belongsTo → Question
├── belongsTo → Result
└── attributes: selected_answer, is_correct
```

## 🎮 Controllers & Their Responsibilities

### QuizController

-   **index()**: List user's quizzes
-   **create()**: Show creation form (manual/AI/hybrid)
-   **store()**: Handle quiz creation with AI integration
-   **take()**: Public quiz taking interface
-   **submit()**: Process quiz submission
-   **generateText()**: AJAX endpoint for AI text generation

### QuestionController

-   CRUD operations for quiz questions
-   **updateText()**: Inline editing via AJAX

### ResultController (ENHANCED - June 2025)

-   **show()**: Display results for authenticated users
-   **guestShow()**: Display results for guests using token
-   **quizResults()**: Display all results for specific quiz
-   **showAiReport()**: Display AI pedagogical report with navigation
-   **generateAiReport()**: Generate AI pedagogical report (Pro Teachers only)

**NEW AI Report Features:**

-   Multiple report navigation with history
-   Template fallback when AI unavailable
-   Comprehensive error handling and debugging
-   Report versioning and metadata tracking
-   Advanced educational analytics generation

**Recent Fix (June 2025):**

-   Added missing `guestShow()` method for guest result access
-   Fixed route parameter binding from `{result:token}` to `{result:guest_token}`
-   Corrected guest redirect logic in QuizController@submit
-   Proper session management for guest data throughout quiz flow

### Admin/AiManagementController

-   **generate()**: AI quiz generation
-   **generateReport()**: AI-powered result analysis
-   Integration with ClaudeService

## 📊 Database Schema Highlights

### Key Tables

-   users (with soft deletes)
-   quizzes (PIN support, AI settings)
-   questions (passage support, root classification)
-   results (guest support, root-wise scoring)
-   answers (tracks each response)
-   ai_usage_logs (tracks AI generation)

### Important Migrations

```
2014_10_12_000000_create_users_table.php
2025_05_27_165155_create_quizzes_table.php
2025_05_27_165156_create_questions_table.php
2025_05_27_165158_create_results_table.php
2025_05_27_165159_create_answers_table.php
2025_05_29_050329_create_ai_usage_logs_table.php
```

## 🔧 Services & Integration

### ClaudeService

-   Integrates with Anthropic Claude API
-   Methods:
    -   generateJuzoorQuiz()
    -   generateEducationalText()
    -   generateQuestionsFromText()
    -   generateCompletion()
-   Supports Arabic, English, Hebrew content

## 🛡️ Security & Middleware

-   **auth**: Standard Laravel authentication
-   **admin**: Custom IsAdmin middleware
-   **guest**: For public pages
-   **SetLocale**: Multi-language support
-   CSRF protection on all forms
-   Guest access via secure tokens

## 🌍 Localization

-   Supports: Arabic (ar), English (en), Hebrew (he)
-   RTL layout support
-   Translation files in resources/lang/

## 📦 Key Packages

-   laravel/breeze (authentication)
-   laravel/sanctum (API tokens)
-   anthropic/claude-sdk (AI integration)
-   Tailwind CSS (styling)
-   Alpine.js (frontend interactivity)
