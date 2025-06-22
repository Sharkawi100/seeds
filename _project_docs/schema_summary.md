# Complete Schema & Routes Summary - جُذور (Juzoor)

Last Updated: June 16, 2025

## Production Architecture

### Server Directory Structure

```
/home/jqfujdmy/
├── roots_app/                    # Laravel application (secure, outside web root)
│   ├── app/Http/Controllers/
│   ├── resources/views/
│   ├── routes/
│   ├── database/
│   ├── storage/
│   ├── .env
│   └── [all Laravel files]
│
└── public_html/                  # Web-accessible directory
    ├── [main domain files]       # www.iseraj.com
    └── roots/                    # Laravel public folder
        ├── index.php             # Entry point
        ├── build/                # Compiled assets
        └── [public assets]
```

### URL Structure

-   **Domain**: `https://www.iseraj.com/roots`
-   **Main Domain**: `https://www.iseraj.com`
-   **Application Root**: `/home/jqfujdmy/roots_app/`
-   **Web Root**: `/home/jqfujdmy/public_html/roots/`

## Database Schema

### Core Tables

#### users

```sql
id, name, email, password (nullable), email_verified_at
google_id, facebook_id, avatar, auth_provider (email|google|facebook)
user_type (student|teacher|admin), is_admin, is_active, is_approved
school_name, grade_level, subjects_taught, experience_years
last_login_at, last_login_ip, login_count
deleted_at, created_at, updated_at
```

#### quizzes

```sql
id, user_id, title, description, subject_id (FK to subjects)
grade_level, pin, is_active, is_demo, is_public
shuffle_questions, shuffle_answers, time_limit, passing_score
show_results, has_submissions, expires_at
settings (JSON), passage_data (JSON)
created_at, updated_at
```

#### subjects

```sql
id, name, slug, is_active, sort_order
created_at, updated_at
```

#### questions

```sql
id, quiz_id, question, passage, passage_title
root_type (jawhar|zihn|waslat|roaya)
depth_level (1|2|3)
options (JSON), correct_answer
created_at, updated_at
```

#### results

```sql
id, quiz_id, user_id (nullable), guest_token, guest_name
scores (JSON: {"jawhar":85,"zihn":92,"waslat":78,"roaya":88})
total_score, expires_at
created_at, updated_at
```

#### answers

```sql
id, question_id, result_id, selected_answer, is_correct
created_at, updated_at
```

### Database Relationships

-   `quizzes.subject_id` → `subjects.id` (belongsTo)
-   `quizzes.user_id` → `users.id` (belongsTo)
-   `questions.quiz_id` → `quizzes.id` (belongsTo)
-   `results.quiz_id` → `quizzes.id` (belongsTo)
-   `results.user_id` → `users.id` (belongsTo, nullable for guests)
-   `answers.question_id` → `questions.id` (belongsTo)
-   `answers.result_id` → `results.id` (belongsTo)

## Complete Routes Reference

### Public Routes (No Authentication)

#### Landing & Information Pages

| Route Name       | URL                    | Method | Description                      |
| ---------------- | ---------------------- | ------ | -------------------------------- |
| `home`           | `/`                    | GET    | Main landing page with PIN entry |
| `about`          | `/about`               | GET    | About جُذور platform             |
| `juzoor.model`   | `/juzoor-model`        | GET    | Educational model explanation    |
| `juzoor.growth`  | `/juzoor-model/growth` | GET    | Growth model details             |
| `question.guide` | `/question-guide`      | GET    | Guide for creating questions     |
| `for.teachers`   | `/for-teachers`        | GET    | Teachers landing page            |
| `for.students`   | `/for-students`        | GET    | Students landing page            |

#### Language & Contact

| Route Name       | URL              | Method | Description                |
| ---------------- | ---------------- | ------ | -------------------------- |
| `lang.switch`    | `/lang/{locale}` | GET    | Switch language (ar/en/he) |
| `contact.show`   | `/contact`       | GET    | Contact form page          |
| `contact.submit` | `/contact`       | POST   | Submit contact form        |

#### Public Quiz Access

| Route Name          | URL                                 | Method | Description             |
| ------------------- | ----------------------------------- | ------ | ----------------------- |
| `quiz.enter-pin`    | `/quiz/enter-pin`                   | POST   | Enter quiz via PIN      |
| `quiz.demo`         | `/quiz/demo`                        | GET    | Demo quiz access        |
| `quiz.take`         | `/quiz/{quiz}/take`                 | GET    | Take quiz (public/auth) |
| `quiz.submit`       | `/quiz/{quiz}/submit`               | POST   | Submit quiz answers     |
| `quiz.guest-start`  | `/quiz/{quiz}/guest-start`          | POST   | Guest name submission   |
| `quiz.guest-result` | `/quiz/result/{result:guest_token}` | GET    | Guest results via token |

### Authentication Routes (Guest Only)

#### Role Selection

| Route Name | URL         | Method | Description                     |
| ---------- | ----------- | ------ | ------------------------------- |
| `register` | `/register` | GET    | Role selection for registration |
| `login`    | `/login`    | GET    | Role selection for login        |

#### Teacher Authentication

| Route Name                 | URL                         | Method | Description            |
| -------------------------- | --------------------------- | ------ | ---------------------- |
| `teacher.login`            | `/teacher/login`            | GET    | Teacher login form     |
| `teacher.register`         | `/teacher/register`         | GET    | Teacher registration   |
| `teacher.pending-approval` | `/teacher/pending-approval` | GET    | Awaiting approval page |

#### Student Authentication

| Route Name         | URL                 | Method | Description          |
| ------------------ | ------------------- | ------ | -------------------- |
| `student.login`    | `/student/login`    | GET    | Student login form   |
| `student.register` | `/student/register` | GET    | Student registration |

#### Standard Auth (Laravel Breeze)

| Route Name            | URL                       | Method   | Description               |
| --------------------- | ------------------------- | -------- | ------------------------- |
| `password.request`    | `/forgot-password`        | GET      | Password reset request    |
| `password.email`      | `/forgot-password`        | POST     | Send reset email          |
| `password.reset`      | `/reset-password/{token}` | GET      | Reset password form       |
| `password.store`      | `/reset-password`         | POST     | Update password           |
| `verification.notice` | `/verify-email`           | GET      | Email verification prompt |
| `logout`              | `/logout`                 | GET/POST | User logout               |

#### Social Authentication

| Route Name        | URL                         | Method | Description              |
| ----------------- | --------------------------- | ------ | ------------------------ |
| `social.login`    | `/auth/{provider}`          | GET    | Redirect to Google OAuth |
| `social.callback` | `/auth/{provider}/callback` | GET    | Handle Google callback   |

**Note**: Currently supports Google OAuth only. Facebook OAuth removed.

#### Dashboard & Profile

| Route Name          | URL                 | Method | Description       |
| ------------------- | ------------------- | ------ | ----------------- |
| `dashboard`         | `/dashboard`        | GET    | User dashboard    |
| `profile.dashboard` | `/profile`          | GET    | Profile dashboard |
| `profile.edit`      | `/profile/edit`     | GET    | Edit profile form |
| `profile.update`    | `/profile`          | PATCH  | Update profile    |
| `profile.destroy`   | `/profile`          | DELETE | Delete account    |
| `password.update`   | `/profile/password` | PUT    | Update password   |

#### Quiz Management (Teachers/Admins)

| Route Name              | URL                             | Method | Description         |
| ----------------------- | ------------------------------- | ------ | ------------------- |
| `quizzes.index`         | `/quizzes`                      | GET    | List all quizzes    |
| `quizzes.create`        | `/quizzes/create`               | GET    | Create quiz form    |
| `quizzes.store`         | `/quizzes`                      | POST   | Store new quiz      |
| `quizzes.show`          | `/quizzes/{quiz}`               | GET    | Show quiz details   |
| `quizzes.edit`          | `/quizzes/{quiz}/edit`          | GET    | Edit quiz form      |
| `quizzes.update`        | `/quizzes/{quiz}`               | PUT    | Update quiz         |
| `quizzes.destroy`       | `/quizzes/{quiz}`               | DELETE | Delete quiz         |
| `quizzes.duplicate`     | `/quizzes/{quiz}/duplicate`     | POST   | Copy quiz           |
| `quizzes.toggle-status` | `/quizzes/{quiz}/toggle-status` | PATCH  | Activate/deactivate |
| `quizzes.results`       | `/quizzes/{quiz}/results`       | GET    | View quiz results   |
| `quizzes.generate-text` | `/quizzes/{quiz}/generate-text` | POST   | AI text generation  |

#### Question Management

| Route Name                      | URL                                         | Method | Description     |
| ------------------------------- | ------------------------------------------- | ------ | --------------- |
| `quizzes.questions.index`       | `/quizzes/{quiz}/questions`                 | GET    | List questions  |
| `quizzes.questions.create`      | `/quizzes/{quiz}/questions/create`          | GET    | Create question |
| `quizzes.questions.store`       | `/quizzes/{quiz}/questions`                 | POST   | Store question  |
| `quizzes.questions.edit`        | `/quizzes/{quiz}/questions/{question}/edit` | GET    | Edit question   |
| `quizzes.questions.update`      | `/quizzes/{quiz}/questions/{question}`      | PUT    | Update question |
| `quizzes.questions.destroy`     | `/quizzes/{quiz}/questions/{question}`      | DELETE | Delete question |
| `quizzes.questions.bulk-edit`   | `/quizzes/{quiz}/questions/bulk-edit`       | GET    | Bulk edit form  |
| `quizzes.questions.bulk-update` | `/quizzes/{quiz}/questions/bulk-update`     | PUT    | Bulk update     |

#### Results Management

| Route Name      | URL                    | Method | Description               |
| --------------- | ---------------------- | ------ | ------------------------- |
| `results.index` | `/results`             | GET    | All user results          |
| `results.quiz`  | `/results/quiz/{quiz}` | GET    | Quiz-specific results     |
| `results.show`  | `/results/{result}`    | GET    | Individual result details |

### Admin Routes (Admin Only)

#### Admin Dashboard

| Route Name        | URL                | Method | Description       |
| ----------------- | ------------------ | ------ | ----------------- |
| `admin.dashboard` | `/admin/dashboard` | GET    | Admin dashboard   |
| `admin.reports`   | `/admin/reports`   | GET    | Analytics reports |
| `admin.settings`  | `/admin/settings`  | GET    | System settings   |

#### User Management

| Route Name                  | URL                                 | Method | Description        |
| --------------------------- | ----------------------------------- | ------ | ------------------ |
| `admin.users.index`         | `/admin/users`                      | GET    | Manage users       |
| `admin.users.create`        | `/admin/users/create`               | GET    | Create user        |
| `admin.users.store`         | `/admin/users`                      | POST   | Store user         |
| `admin.users.show`          | `/admin/users/{user}`               | GET    | View user          |
| `admin.users.edit`          | `/admin/users/{user}/edit`          | GET    | Edit user          |
| `admin.users.update`        | `/admin/users/{user}`               | PUT    | Update user        |
| `admin.users.destroy`       | `/admin/users/{user}`               | DELETE | Delete user        |
| `admin.users.toggle-status` | `/admin/users/{user}/toggle-status` | POST   | Toggle user status |
| `admin.users.impersonate`   | `/admin/users/{user}/impersonate`   | GET    | Impersonate user   |
| `admin.stop-impersonation`  | `/admin/stop-impersonation`         | GET    | Stop impersonation |

#### Subject Management

| Route Name               | URL                              | Method | Description     |
| ------------------------ | -------------------------------- | ------ | --------------- |
| `admin.subjects.index`   | `/admin/subjects`                | GET    | Manage subjects |
| `admin.subjects.create`  | `/admin/subjects/create`         | GET    | Create subject  |
| `admin.subjects.store`   | `/admin/subjects`                | POST   | Store subject   |
| `admin.subjects.edit`    | `/admin/subjects/{subject}/edit` | GET    | Edit subject    |
| `admin.subjects.update`  | `/admin/subjects/{subject}`      | PUT    | Update subject  |
| `admin.subjects.destroy` | `/admin/subjects/{subject}`      | DELETE | Delete subject  |

#### AI & System Management

| Route Name            | URL                    | Method | Description      |
| --------------------- | ---------------------- | ------ | ---------------- |
| `admin.ai.index`      | `/admin/ai`            | GET    | AI management    |
| `admin.ai.generate`   | `/admin/ai/generate`   | POST   | Generate content |
| `admin.logs.analyzer` | `/admin/logs/analyzer` | GET    | Log analyzer     |
| `admin.logs.clear`    | `/admin/logs/clear`    | POST   | Clear logs       |

## File Paths Reference

### Production (Namecheap Shared Hosting)

| Component            | Path                                                |
| -------------------- | --------------------------------------------------- |
| **Application Root** | `/home/jqfujdmy/roots_app/`                         |
| **Web Directory**    | `/home/jqfujdmy/public_html/roots/`                 |
| **Entry Point**      | `/home/jqfujdmy/public_html/roots/index.php`        |
| **Environment**      | `/home/jqfujdmy/roots_app/.env`                     |
| **Views**            | `/home/jqfujdmy/roots_app/resources/views/`         |
| **Controllers**      | `/home/jqfujdmy/roots_app/app/Http/Controllers/`    |
| **Routes**           | `/home/jqfujdmy/roots_app/routes/`                  |
| **Logs**             | `/home/jqfujdmy/roots_app/storage/logs/laravel.log` |
| **Cache**            | `/home/jqfujdmy/roots_app/storage/framework/cache/` |

### Local Development (Windows XAMPP)

| Component            | Path                                          |
| -------------------- | --------------------------------------------- |
| **Application Root** | `C:\xampp\htdocs\roots\`                      |
| **Web Directory**    | `C:\xampp\htdocs\roots\public\`               |
| **Environment**      | `C:\xampp\htdocs\roots\.env`                  |
| **Views**            | `C:\xampp\htdocs\roots\resources\views\`      |
| **Controllers**      | `C:\xampp\htdocs\roots\app\Http\Controllers\` |

## Configuration Notes

### Subdirectory Installation

-   Laravel app files stored outside web root for security
-   Public folder contents served from `/public_html/roots/`
-   All URLs include `/roots` prefix
-   Configuration: `APP_URL=https://www.iseraj.com/roots`

### Key Features

-   **4-Roots Educational Model**: jawhar, zihn, waslat, roaya
-   **Multi-language Content**: Arabic, English, Hebrew (interface Arabic only)
-   **Guest Access**: PIN-based quiz taking with 7-day result tokens
-   **AI Integration**: Claude-powered content generation
-   **Social Authentication**: Google OAuth support
-   **Role-based Access**: Students, Teachers, Admins

# Complete Schema & Routes Summary - جُذور (Juzoor)

Last Updated: June 22, 2025

## Production Architecture

### Server Directory Structure

```
/home/jqfujdmy/
├── roots_app/                    # Laravel application (secure, outside web root)
│   ├── app/Http/Controllers/
│   ├── resources/views/
│   ├── routes/
│   ├── database/
│   ├── storage/
│   ├── .env
│   └── [all Laravel files]
│
└── public_html/                  # Web-accessible directory
    ├── [main domain files]       # www.iseraj.com
    └── roots/                    # Laravel public folder
        ├── index.php             # Entry point
        ├── build/                # Compiled assets
        └── [public assets]
```

### URL Structure

-   **Domain**: `https://www.iseraj.com/roots`
-   **Main Domain**: `https://www.iseraj.com`
-   **Application Root**: `/home/jqfujdmy/roots_app/`
-   **Web Root**: `/home/jqfujdmy/public_html/roots/`

## Database Schema

### Core Tables

#### users

```sql
id, name, email, password (nullable), email_verified_at
google_id, facebook_id, avatar, auth_provider (email|google|facebook)
user_type (student|teacher|admin), is_admin, is_active, is_approved
school_name, grade_level, subjects_taught, experience_years
last_login_at, last_login_ip, login_count

-- NEW: Subscription fields
subscription_active (boolean), subscription_expires_at (timestamp)
lemon_squeezy_customer_id (varchar), subscription_plan (varchar)
subscription_status (enum: active|cancelled|expired|paused)

deleted_at, created_at, updated_at
```

#### quizzes

```sql
id, user_id, title, description, subject_id (FK to subjects)
grade_level, pin, is_active, is_demo, is_public
shuffle_questions, shuffle_answers, time_limit, passing_score
max_attempts, scoring_method (enum: latest|average|highest|first_only)
show_results, has_submissions, expires_at
settings (JSON), passage_data (JSON)
created_at, updated_at
```

#### subjects

```sql
id, name, slug, is_active, sort_order
created_at, updated_at
```

#### questions

```sql
id, quiz_id, question, passage, passage_title
root_type (jawhar|zihn|waslat|roaya), depth_level (1|2|3)
options (JSON), correct_answer, sort_order
created_at, updated_at
```

#### results

```sql
id, quiz_id, user_id (nullable), guest_name (nullable), guest_token
school_class, scores (JSON), total_score, percentage
attempt_number, is_latest_attempt, ip_address, user_agent
created_at, updated_at
```

#### answers

```sql
id, result_id, question_id, selected_answer, is_correct
created_at, updated_at
```

### NEW: Subscription System Tables

#### subscription_plans

```sql
id, name, lemon_squeezy_variant_id
monthly_quiz_limit, monthly_ai_text_limit, monthly_ai_quiz_limit
price_monthly, is_active
created_at, updated_at
```

#### subscriptions

```sql
id, user_id, lemon_squeezy_subscription_id, lemon_squeezy_customer_id
status, plan_name, plan_id (FK to subscription_plans)
current_period_start, current_period_end, trial_ends_at
created_at, updated_at
```

#### monthly_quotas

```sql
id, user_id, year, month
quiz_count, ai_text_requests, ai_quiz_requests
created_at, updated_at
```

#### ai_usage_logs

```sql
id, type, model, count, user_id, metadata (JSON)
created_at, updated_at
```

## Route Structure

### Public Routes

#### Landing & Information

-   GET `/` - Welcome page with PIN entry
-   GET `/about` - About جُذور model
-   GET `/juzoor-model` - Educational model explanation
-   GET `/question-guide` - Question writing guide
-   GET `/for-teachers` - Teachers landing page
-   GET `/for-students` - Students landing page

#### Quiz Taking (Public Access)

-   GET `/quiz/pin` - PIN entry form
-   POST `/quiz/enter-pin` - Process PIN entry
-   GET `/quiz/{quiz:pin}` - Take quiz by PIN
-   POST `/quiz/{quiz}/submit` - Submit quiz answers

#### Guest Results

-   GET `/quiz/{quiz}/result/{token}` - View guest results

### Authenticated Routes

#### Authentication

-   GET `/login` - Login page
-   POST `/login` - Process login
-   GET `/register` - Registration page
-   POST `/register` - Process registration
-   POST `/logout` - Logout
-   GET `/forgot-password` - Password reset request
-   POST `/forgot-password` - Send reset email
-   GET `/reset-password/{token}` - Password reset form
-   POST `/reset-password` - Process password reset

#### Dashboard & Profile

-   GET `/dashboard` - User dashboard
-   GET `/profile` - User profile page
-   GET `/profile/edit` - Edit profile
-   PATCH `/profile` - Update profile
-   DELETE `/profile` - Delete account

### Teacher/Admin Routes

#### Quiz Management

-   GET `/quizzes` - List user's quizzes
-   GET `/quizzes/create` - Create quiz form
-   POST `/quizzes/create-step-1` - Process basic info
-   GET `/quizzes/{quiz}` - Show quiz details
-   GET `/quizzes/{quiz}/edit` - Edit quiz
-   PUT `/quizzes/{quiz}` - Update quiz
-   DELETE `/quizzes/{quiz}` - Delete quiz
-   PATCH `/quizzes/{quiz}/toggle-status` - Toggle active status

#### Quiz Creation (AI Integration)

-   POST `/quizzes/{quiz}/generate-text` - Generate educational text
-   POST `/quizzes/{quiz}/generate-questions` - Generate questions

#### Question Management

-   GET `/quizzes/{quiz}/questions` - List questions
-   GET `/quizzes/{quiz}/questions/create` - Create question
-   POST `/quizzes/{quiz}/questions` - Store question
-   GET `/quizzes/{quiz}/questions/{question}/edit` - Edit question
-   PUT `/quizzes/{quiz}/questions/{question}` - Update question
-   DELETE `/quizzes/{quiz}/questions/{question}` - Delete question

#### Results Management

-   GET `/results` - All user results
-   GET `/results/quiz/{quiz}` - Quiz-specific results
-   GET `/results/{result}` - Individual result details

### NEW: Subscription Routes

#### User Subscription Management

-   GET `/subscription/upgrade` - Subscription plans page
-   POST `/subscription/checkout` - Create Lemon Squeezy checkout
-   GET `/subscription/success` - Payment success page
-   GET `/subscription/manage` - Manage subscription

#### Webhooks

-   POST `/webhooks/lemonsqueezy` - Lemon Squeezy webhook handler

### Admin Routes (Admin Only)

#### Dashboard & Reports

-   GET `/admin/dashboard` - Admin dashboard
-   GET `/admin/reports` - Analytics reports
-   GET `/admin/settings` - System settings

#### User Management

-   GET `/admin/users` - List all users
-   GET `/admin/users/create` - Create user form
-   POST `/admin/users` - Store new user
-   GET `/admin/users/{user}` - Show user details
-   GET `/admin/users/{user}/edit` - Edit user form
-   PUT `/admin/users/{user}` - Update user
-   DELETE `/admin/users/{user}` - Delete user
-   POST `/admin/users/{user}/toggle-status` - Toggle user status
-   GET `/admin/users/{user}/impersonate` - Start impersonation

#### NEW: Subscription Plan Management

-   GET `/admin/subscription-plans` - List subscription plans
-   GET `/admin/subscription-plans/create` - Create plan form
-   POST `/admin/subscription-plans` - Store new plan
-   GET `/admin/subscription-plans/{plan}` - Show plan details
-   GET `/admin/subscription-plans/{plan}/edit` - Edit plan form
-   PUT `/admin/subscription-plans/{plan}` - Update plan
-   DELETE `/admin/subscription-plans/{plan}` - Delete plan
-   PATCH `/admin/subscription-plans/{plan}/toggle` - Toggle plan status

#### NEW: Subscription User Management

-   GET `/admin/subscription-plans-users` - List subscription users
-   GET `/admin/users/{user}/manage-subscription` - Manage user subscription
-   PUT `/admin/users/{user}/update-subscription` - Update user subscription

#### Quiz Management (Admin)

-   GET `/admin/quizzes` - List all quizzes
-   Resource routes for admin quiz management
-   POST `/admin/quizzes/{quiz}/toggle-status` - Toggle quiz status

#### AI Management

-   GET `/admin/ai` - AI management dashboard
-   POST `/admin/ai/generate` - Generate with AI

## Key Features & Business Logic

### Subscription System

-   **Freemium Model**: Manual quiz creation free, AI features require subscription
-   **Monthly Quotas**: 40 quiz limit per month for all teachers
-   **Lemon Squeezy Integration**: Secure payment processing
-   **Admin Management**: Full subscription control via admin interface

### AI Integration

-   **Feature Gating**: AI text/quiz generation behind paywall
-   **Claude API**: Anthropic Claude for Arabic content generation
-   **Smart Fallbacks**: Manual question creation for non-subscribers

### Quiz Creation Flow

-   **3-Step Wizard**: Basic info → Text source → Question settings
-   **Multiple Text Sources**: AI generation, manual input, or no text
-   **4-Roots Model**: جَوهر (Jawhar), ذِهن (Zihn), وَصلات (Waslat), رُؤية (Roaya)

### Access Control

-   **PIN System**: Guest access via 6-character PIN
-   **Attempt Tracking**: Multiple attempts with scoring methods
-   **Result Persistence**: 7-day token access for guests

### Multilingual Support

-   **Primary Language**: Arabic (RTL)
-   **Secondary Languages**: English, Hebrew
-   **Content Creation**: All three languages supported

## Security & Performance

### Authentication & Authorization

-   **Laravel Breeze**: Built-in authentication
-   **Social Login**: Google OAuth integration
-   **Role-Based Access**: Student/Teacher/Admin levels
-   **CSRF Protection**: All forms protected

### Database Optimization

-   **Indexes**: Strategic indexing for performance
-   **Soft Deletes**: User data preservation
-   **JSON Fields**: Flexible settings storage
-   **UTF-8 Support**: Proper Arabic text handling

### Hosting Considerations

-   **Shared Hosting**: Optimized for Namecheap hosting
-   **Memory Management**: Efficient resource usage
-   **File Storage**: Minimal external dependencies

## Current System Status

### Implemented Features ✅

-   Complete subscription system with Lemon Squeezy
-   Monthly quota tracking and enforcement
-   AI feature gating for subscribers
-   Admin subscription management interface
-   Profile management with subscription status
-   Arabic RTL interface with English/Hebrew support

### Active Integrations

-   **Payment**: Lemon Squeezy ($15/month Pro Teacher)
-   **AI**: Anthropic Claude API
-   **Analytics**: Built-in usage tracking
-   **Social Auth**: Google OAuth

### Performance Metrics

-   **Free Users**: 5 quizzes/month, manual creation only
-   **Subscribers**: 40 quizzes/month + unlimited AI features
-   **Guest Access**: 7-day result retention
-   **Admin Users**: Unlimited everything

---

_Last Updated: June 22, 2025 - Added comprehensive subscription system with Lemon Squeezy integration_
