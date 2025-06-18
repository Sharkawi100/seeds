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
