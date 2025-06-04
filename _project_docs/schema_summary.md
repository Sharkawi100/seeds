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
