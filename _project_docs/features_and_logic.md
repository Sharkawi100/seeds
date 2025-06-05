# Features & Business Logic - Juzoor

Last Updated: June 2025

## Core Features

### 1. Educational Model (جُذور)

-   4 Roots: جَوهر (Essence), ذِهن (Mind), وَصلات (Connections), رُؤية (Vision)
-   3 Depth Levels per root (Surface, Medium, Deep)
-   Holistic assessment approach

### 2. Quiz Creation Modes

-   **Manual**: Teacher creates questions manually
-   **AI-Powered**: Claude generates questions
-   **Hybrid**: AI generates, teacher edits

### 3. Access Methods

-   **Authenticated**: Teachers create, students take with accounts
-   **Guest Access**: Via 6-character PIN, results stored for 7 days
-   **Public Sharing**: Direct quiz links

### 4. AI Integration

-   Educational text generation (stories, articles, dialogues)
-   Question generation from text
-   Balanced question distribution across roots
-   Support for Arabic, English, Hebrew

### 5. Enhanced Authentication System

-   **Multi-method Login**: Email/Password, Google OAuth, Facebook OAuth
-   **Security Features**:
    -   Login attempt tracking (5 attempts = 15 min lockout)
    -   New device detection with email alerts
    -   Password strength requirements
    -   Password history (prevents reusing last 5)
    -   Active session management
-   **User Types**: Student, Teacher, Admin
-   **Social Login**: Auto-registration with OAuth providers
-   **Guest Improvements**: Better PIN access flow

### 6. Security Implementation

-   **Login Security**:
    -   Failed attempt tracking in `login_attempts` table
    -   Account lockout mechanism
    -   IP-based tracking
    -   Device fingerprinting
-   **Password Policies**:
    -   Minimum 8 characters
    -   Mixed case, numbers, special characters required
    -   Password history tracking
    -   Force password change option for admins
-   **Session Management**:
    -   Track all active sessions
    -   Logout from other devices
    -   Last login display

### 7. Role-Specific Authentication (NEW - June 2025)

-   **Separate Login/Registration Flows**:
    -   Teacher portal with approval workflow
    -   Student portal with simplified registration
    -   Role selection page at /login and /register
-   **Teacher Features**:
    -   Required fields: school, subjects taught, experience
    -   Admin approval required before access
    -   Pending approval page while waiting
-   **Student Features**:
    -   Optional parent email
    -   PIN login (school code + student ID)
    -   Auto-approved registration
-   **Database Enhancements**:
    -   Role-specific columns added
    -   JSON fields for extensible data
    -   Performance indexes

## Updated Business Rules

-   Social login users don't need passwords
-   Account locks after 5 failed login attempts (15 minutes)
-   New device logins trigger email notifications
-   Passwords cannot be reused (last 5 tracked)
-   Admin accounts can force password changes
-   Teachers must be approved by admin before creating quizzes
-   Students are auto-approved upon registration
-   Default user type for social login is 'student'

### 8. Results & Analytics

-   Root-wise scoring visualization (radar chart)
-   Detailed answer review
-   AI-generated performance reports
-   Progress tracking over time

## User Roles

1. **Admin**: Full system access, user management, teacher approval
2. **Teacher**: Create quizzes, view all results (after approval)
3. **Student**: Take quizzes, view own results
4. **Guest**: Take quiz via PIN, temporary results

## Business Rules

-   Quizzes must have at least 1 question
-   Each question must have 2-6 options
-   PIN codes are 6 characters (alphanumeric)
-   Guest results expire after 7 days
-   AI usage is tracked and limited
-   Teachers require approval before access
-   Students can use PIN or email login
