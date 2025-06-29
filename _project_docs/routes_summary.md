# Routes Summary - Juzoor

Last Updated: June 2025

## Public Routes (No Authentication Required)

### Landing & Information Pages

-   GET / # Main landing page with PIN entry
-   GET /about # About جُذور platform
-   GET /juzoor-model # Educational model explanation
-   GET /juzoor-model/growth # Growth model details
-   GET /question-guide # Guide for creating questions
-   GET /for-teachers # Teachers landing page (NEW)
-   GET /for-students # Students landing page (NEW)

### Contact

-   GET /contact # Contact form page
-   POST /contact # Submit contact form

### Language Switching

-   GET /lang/{locale} # Switch language (ar/en/he)

### Quiz Public Access

-   POST /quiz/enter-pin # Enter quiz via PIN
-   GET /quiz/demo # Demo quiz
-   GET /quiz/{quiz}/take # Take quiz (guest allowed)
-   POST /quiz/{quiz}/submit # Submit answers
-   POST /quiz/{quiz}/guest-start # Guest name submission

### Results Viewing

-   GET /results/{result} # View results (authenticated users)
-   GET /quiz/result/{result:guest_token} # View results (guests with token)

## Authentication Routes

### Role Selection (NEW)

-   GET /login # Role selection page
-   GET /register # Role selection page

### Teacher Authentication (NEW)

-   GET /teacher/login # Teacher login form
-   POST /teacher/login # Process teacher login
-   GET /teacher/register # Teacher registration form
-   POST /teacher/register # Process teacher registration
-   GET /teacher/pending-approval # Pending approval page (auth required)

### Student Authentication

-   GET /student/login # Student login form
-   POST /student/login # Process student login (email/password)
-   POST /student/pin-login # Student PIN login (school code + student ID)
-   GET /student/register # Student registration form
-   POST /student/register # Process student registration

### Standard Auth

-   GET/POST /forgot-password # Password reset
-   GET /auth/{provider} # Social login redirect (Google)
-   GET /auth/{provider}/callback # Social login callback
-   POST /logout # Logout
-   GET /logout-now # Simple logout (no CSRF)

## Authenticated Routes

### Dashboard

-   GET /dashboard # User dashboard (role-based content)

### Profile Management

-   GET /profile # Profile dashboard
-   GET /profile/edit # Edit profile form
-   PATCH /profile # Update profile
-   DELETE /profile # Delete account
-   POST /profile/avatar # Update avatar
-   GET /profile/completion # Profile completion status
-   GET /profile/sessions # Active sessions
-   POST /profile/logout-other-devices # Logout other devices
-   PATCH /profile/preferences # Update preferences
-   PATCH /profile/privacy # Update privacy settings
-   PUT /profile/password # Update password

### Quiz Management (Teachers/Admins)

-   GET /quizzes # List user's quizzes
-   GET /quizzes/create # Create quiz form
-   POST /quizzes # Store new quiz
-   GET /quizzes/{quiz} # Show quiz details
-   GET /quizzes/{quiz}/edit # Edit quiz form
-   PUT /quizzes/{quiz} # Update quiz
-   DELETE /quizzes/{quiz} # Delete quiz
-   POST /quizzes/{quiz}/duplicate # Duplicate quiz
-   POST /quizzes/generate-text # AI text generation

### Question Management

-   GET /quizzes/{quiz}/questions # List questions
-   GET /quizzes/{quiz}/questions/create # Create question form
-   POST /quizzes/{quiz}/questions # Store question
-   GET /quizzes/{quiz}/questions/bulk-edit # Bulk edit form
-   PUT /quizzes/{quiz}/questions/bulk-update # Bulk update
-   DELETE /quizzes/{quiz}/questions/bulk-delete # Bulk delete
-   GET /quizzes/{quiz}/questions/{question}/edit # Edit question
-   PUT /quizzes/{quiz}/questions/{question} # Update question
-   DELETE /quizzes/{quiz}/questions/{question} # Delete question
-   POST /quizzes/{quiz}/questions/{question}/update-text # AJAX text update
-   POST /quizzes/{quiz}/questions/{question}/clone # Clone question

### Results Management

-   GET /results # List results (teachers see all, students see own)
-   GET /results/quiz/{quiz} # Results for specific quiz

### Stop Impersonation (Outside Admin)

-   GET /admin/stop-impersonation # Stop impersonating user

## Admin Routes (prefix: /admin, Admin Only)

### Dashboard & Reports

-   GET /admin/dashboard # Admin dashboard
-   GET /admin/reports # Reports and analytics
-   GET /admin/settings # Admin settings

### User Management

-   GET /admin/users # List all users
-   GET /admin/users/create # Create user form
-   POST /admin/users # Store new user
-   GET /admin/users/{user} # Show user details
-   GET /admin/users/{user}/edit # Edit user form
-   PUT /admin/users/{user} # Update user
-   DELETE /admin/users/{user} # Delete user
-   POST /admin/users/{user}/toggle-status # Toggle active status
-   POST /admin/users/{user}/update-role # Update user role
-   POST /admin/users/{user}/disconnect-social # Disconnect social account
-   GET /admin/users/{user}/impersonate # Start impersonation
-   GET /admin/users-export # Export users to CSV

### Quiz Management (Admin)

-   GET /admin/quizzes # List all quizzes
-   Resource routes for admin quiz management
-   POST /admin/quizzes/{quiz}/toggle-status # Toggle quiz status
-   GET /admin/quizzes-export # Export quizzes to CSV

### AI Management

-   GET /admin/ai # AI management dashboard
-   POST /admin/ai/generate # Generate with AI
-   POST /admin/ai/quiz/{quiz}/report # Generate AI report

## Route Protection

### Middleware Groups

1. **guest**: Login/register pages (redirect if authenticated)
2. **auth**: Requires authentication
3. **admin**: Requires admin role (IsAdmin middleware)
4. **teacher**: Checks if user is teacher or admin

### Special Cases

-   PIN-based quiz access doesn't require authentication
-   Results can be viewed by guests with valid token
-   Stop impersonation accessible during impersonation only

## Recent Changes (June 2025)

1. Added role-specific authentication routes
2. Created landing pages for teachers and students
3. Improved route organization with clear sections
4. Added bulk operations for questions
5. Enhanced admin user management routes
6. Fixed stop-impersonation route placement

### Quiz Management Actions (NEW)

-   PATCH /quizzes/{quiz}/toggle-status # Toggle active status
-   GET /quizzes/{quiz}/results # View quiz results
-   POST /quizzes/{quiz}/duplicate # Copy quiz

```markdown
### Feature Coverage

-   ✅ **User Management**: Registration, authentication, profiles
-   ✅ **Quiz System**: Creation, management, taking, results
-   ✅ **AI Integration**: Text generation, question creation, **NEW: Pedagogical reports**
-   ✅ **Subscription System**: Plans, payments, usage tracking
-   ✅ **Cancellation Management**: Advanced retention system
-   ✅ **Admin Interface**: User, subscription, and contact management
-   ✅ **Analytics**: Usage tracking, cancellation insights, **NEW: Educational analytics**
-   ✅ **Multi-language**: Arabic (primary), English, Hebrew
-   ✅ **NEW: AI Report System**: Advanced pedagogical analysis with versioning

### NEW: AI Pedagogical Reports (June 2025)

-   **Advanced Analytics**: Deep educational insights beyond basic statistics
-   **Report Versioning**: Multiple reports per quiz with full navigation
-   **Fallback System**: Template-based reports when AI unavailable
-   **Quota Integration**: Unified monthly quota system (40 units)
-   **Educational Value**: Misconception detection, learning pattern analysis
-   **Professional UI**: Clean, printable reports with modern navigation
```
