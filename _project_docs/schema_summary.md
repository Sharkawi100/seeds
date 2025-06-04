# Complete Database Schema - Juzoor (Updated)

Last Updated: December 2024

## Tables & Relationships

### users

-   id, name, email, password (nullable), email_verified_at
-   is_admin, is_school
-   **NEW**: google_id, facebook_id, avatar, auth_provider (email|google|facebook)
-   **NEW**: user_type (student|teacher|admin), school_name, grade_level
-   **NEW**: last_login_at, last_login_ip, login_count
-   **NEW**: account_locked, locked_until, force_password_change
-   **NEW**: password_changed_at
-   Has many: quizzes, results, user_logins

### login_attempts (NEW)

-   id, email, ip_address, user_agent
-   successful (boolean), attempted_at, locked_until
-   Indexes: email+attempted_at, ip_address+attempted_at

### user_logins (NEW)

-   id, user_id (FK), ip_address, user_agent
-   device_type, browser, platform, location
-   latitude, longitude, is_trusted
-   logged_in_at, logged_out_at
-   Belongs to: User

### password_histories (NEW)

-   id, user_id (FK), password (hashed)
-   created_at
-   Belongs to: User

### quizzes

-   id, user_id, title, subject (arabic|english|hebrew)
-   grade_level (1-9), settings (JSON), pin
-   Has many: questions, results

### questions

-   id, quiz_id, question, passage, passage_title
-   root_type (jawhar|zihn|waslat|roaya)
-   depth_level (1|2|3)
-   options (JSON array), correct_answer
-   Has many: answers

### results

-   id, quiz_id, user_id (nullable), guest_token, guest_name
-   scores (JSON: {jawhar: %, zihn: %, waslat: %, roaya: %})
-   total_score, expires_at
-   Has many: answers

### answers

-   id, question_id, result_id
-   selected_answer, is_correct

### ai_usage_logs

-   id, type, model, count, user_id, created_at

Database Schema Updates
Users Table Additions
sqlgoogle_id VARCHAR(255) NULL
facebook_id VARCHAR(255) NULL  
avatar VARCHAR(255) NULL
auth_provider ENUM('email', 'google', 'facebook') DEFAULT 'email'
user_type VARCHAR(20) DEFAULT 'student'
school_name VARCHAR(255) NULL
grade_level INT NULL
last_login_at TIMESTAMP NULL
last_login_ip VARCHAR(45) NULL
login_count INT DEFAULT 0
account_locked BOOLEAN DEFAULT FALSE
locked_until TIMESTAMP NULL
force_password_change BOOLEAN DEFAULT FALSE
password_changed_at TIMESTAMP NULL
New Tables

login_attempts - Security tracking
user_logins - Session management
password_histories - Password reuse prevention

Configuration
OAuth Setup (.env)
envGOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-secret
GOOGLE_REDIRECT_URL=https://www.iseraj.com/roots/auth/google/callback
Security Settings

Max login attempts: 5
Lockout duration: 15 minutes
Password history: 5 passwords
Session lifetime: 120 minutes

API Endpoints
GET /auth/{provider} # Redirect to OAuth provider
GET /auth/{provider}/callback # Handle OAuth callback
POST /profile/logout-other-devices # Logout from other devices
Future Enhancements

Two-Factor Authentication (2FA)
Biometric authentication
Magic link login
SSO for schools
Advanced threat detection
Login with QR code

# Common Route Names and File Paths - جُذور (Juzoor)

## Common Route Names

| Purpose                  | Route Name              | URL                       | Method |
| ------------------------ | ----------------------- | ------------------------- | ------ |
| **Public Pages**         |
| Home Page                | `home`                  | `/`                       | GET    |
| About Page               | `about`                 | `/about`                  | GET    |
| Juzoor Model             | `juzoor.model`          | `/juzoor-model`           | GET    |
| Question Guide           | `question.guide`        | `/question-guide`         | GET    |
| Contact Page             | `contact.show`          | `/contact`                | GET    |
| Submit Contact           | `contact.submit`        | `/contact`                | POST   |
| Language Switch          | `lang.switch`           | `/lang/{locale}`          | GET    |
| **Authentication**       |
| Login Page               | `login`                 | `/login`                  | GET    |
| Login Submit             | `login`                 | `/login`                  | POST   |
| Register Page            | `register`              | `/register`               | GET    |
| Register Submit          | `register`              | `/register`               | POST   |
| Logout                   | `logout`                | `/logout`                 | POST   |
| Forgot Password          | `password.request`      | `/forgot-password`        | GET    |
| Password Reset           | `password.reset`        | `/reset-password/{token}` | GET    |
| **Quiz Public Access**   |
| Enter PIN                | `quiz.enter-pin`        | `/quiz/enter-pin`         | POST   |
| Demo Quiz                | `quiz.demo`             | `/quiz/demo`              | GET    |
| Take Quiz                | `quiz.take`             | `/quiz/{quiz}/take`       | GET    |
| Submit Quiz              | `quiz.submit`           | `/quiz/{quiz}/submit`     | POST   |
| View Results             | `results.show`          | `/results/{result}`       | GET    |
| **Authenticated Routes** |
| Dashboard                | `dashboard`             | `/dashboard`              | GET    |
| Profile                  | `profile.edit`          | `/profile`                | GET    |
| Update Profile           | `profile.update`        | `/profile`                | PATCH  |
| **Quiz Management**      |
| Quiz List                | `quizzes.index`         | `/quizzes`                | GET    |
| Create Quiz              | `quizzes.create`        | `/quizzes/create`         | GET    |
| Store Quiz               | `quizzes.store`         | `/quizzes`                | POST   |
| Show Quiz                | `quizzes.show`          | `/quizzes/{quiz}`         | GET    |
| Edit Quiz                | `quizzes.edit`          | `/quizzes/{quiz}/edit`    | GET    |
| Update Quiz              | `quizzes.update`        | `/quizzes/{quiz}`         | PUT    |
| Delete Quiz              | `quizzes.destroy`       | `/quizzes/{quiz}`         | DELETE |
| Generate Text            | `quizzes.generate-text` | `/quizzes/generate-text`  | POST   |
| **Admin Routes**         |
| Admin Dashboard          | `admin.dashboard`       | `/admin/dashboard`        | GET    |
| Admin Users              | `admin.users.index`     | `/admin/users`            | GET    |
| Admin AI                 | `admin.ai.index`        | `/admin/ai`               | GET    |

## File Paths

### Production Server (Namecheap)

| Description          | Path                                                  |
| -------------------- | ----------------------------------------------------- |
| **Application Root** | `/home/jqfujdmy/roots_app`                            |
| Public Directory     | `/home/jqfujdmy/public_html/roots`                    |
| Views                | `/home/jqfujdmy/roots_app/resources/views`            |
| Controllers          | `/home/jqfujdmy/roots_app/app/Http/Controllers`       |
| Models               | `/home/jqfujdmy/roots_app/app/Models`                 |
| Routes               | `/home/jqfujdmy/roots_app/routes`                     |
| Storage              | `/home/jqfujdmy/roots_app/storage`                    |
| Logs                 | `/home/jqfujdmy/roots_app/storage/logs/laravel.log`   |
| Cache                | `/home/jqfujdmy/roots_app/storage/framework/cache`    |
| Sessions             | `/home/jqfujdmy/roots_app/storage/framework/sessions` |
| Compiled Views       | `/home/jqfujdmy/roots_app/storage/framework/views`    |
| Environment File     | `/home/jqfujdmy/roots_app/.env`                       |
| Entry Point          | `/home/jqfujdmy/public_html/roots/index.php`          |

### Local Development (Windows XAMPP)

| Description          | Path                                               |
| -------------------- | -------------------------------------------------- |
| **Application Root** | `C:\xampp\htdocs\roots`                            |
| Public Directory     | `C:\xampp\htdocs\roots\public`                     |
| Views                | `C:\xampp\htdocs\roots\resources\views`            |
| Controllers          | `C:\xampp\htdocs\roots\app\Http\Controllers`       |
| Models               | `C:\xampp\htdocs\roots\app\Models`                 |
| Routes               | `C:\xampp\htdocs\roots\routes`                     |
| Storage              | `C:\xampp\htdocs\roots\storage`                    |
| Logs                 | `C:\xampp\htdocs\roots\storage\logs\laravel.log`   |
| Cache                | `C:\xampp\htdocs\roots\storage\framework\cache`    |
| Sessions             | `C:\xampp\htdocs\roots\storage\framework\sessions` |
| Compiled Views       | `C:\xampp\htdocs\roots\storage\framework\views`    |
| Environment File     | `C:\xampp\htdocs\roots\.env`                       |

### Key View Files

| View         | Local Path                                                      | Production Path                                                    |
| ------------ | --------------------------------------------------------------- | ------------------------------------------------------------------ |
| Welcome      | `C:\xampp\htdocs\roots\resources\views\welcome.blade.php`       | `/home/jqfujdmy/roots_app/resources/views/welcome.blade.php`       |
| Login        | `C:\xampp\htdocs\roots\resources\views\auth\login.blade.php`    | `/home/jqfujdmy/roots_app/resources/views/auth/login.blade.php`    |
| Dashboard    | `C:\xampp\htdocs\roots\resources\views\dashboard.blade.php`     | `/home/jqfujdmy/roots_app/resources/views/dashboard.blade.php`     |
| App Layout   | `C:\xampp\htdocs\roots\resources\views\layouts\app.blade.php`   | `/home/jqfujdmy/roots_app/resources/views/layouts/app.blade.php`   |
| Guest Layout | `C:\xampp\htdocs\roots\resources\views\layouts\guest.blade.php` | `/home/jqfujdmy/roots_app/resources/views/layouts/guest.blade.php` |

### Important Route Files

| File        | Local Path                              | Production Path                            |
| ----------- | --------------------------------------- | ------------------------------------------ |
| Web Routes  | `C:\xampp\htdocs\roots\routes\web.php`  | `/home/jqfujdmy/roots_app/routes/web.php`  |
| API Routes  | `C:\xampp\htdocs\roots\routes\api.php`  | `/home/jqfujdmy/roots_app/routes/api.php`  |
| Auth Routes | `C:\xampp\htdocs\roots\routes\auth.php` | `/home/jqfujdmy/roots_app/routes/auth.php` |

## Quick Commands for Path Navigation

### Windows (Local)

```cmd
# Navigate to project root
cd C:\xampp\htdocs\roots

# Open in VSCode
code C:\xampp\htdocs\roots

# View logs
type storage\logs\laravel.log
```
