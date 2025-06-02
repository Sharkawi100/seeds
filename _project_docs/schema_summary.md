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
