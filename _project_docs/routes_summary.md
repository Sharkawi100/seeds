# Routes Summary - Juzoor

Last Updated: June 2025

## Public Routes

-   GET / # Landing page with PIN entry
-   POST /quiz/enter-pin # Enter quiz via PIN
-   GET /quiz/{quiz}/take # Take quiz (guest allowed)
-   POST /quiz/{quiz}/submit # Submit answers
-   GET /results/{result} # View results (with token)

## Role Selection Routes (NEW)

-   GET /login # Role selection page
-   GET /register # Role selection page

## Teacher Auth Routes (NEW)

-   GET /teacher/login # Teacher login form
-   POST /teacher/login # Process teacher login
-   GET /teacher/register # Teacher registration form
-   POST /teacher/register # Process teacher registration
-   GET /teacher/pending-approval # Pending approval page (auth required)

## Student Auth Routes (NEW)

-   GET /student/login # Student login form
-   POST /student/login # Process student login
-   POST /student/pin-login # Student PIN login
-   GET /student/register # Student registration form
-   POST /student/register # Process student registration

## Auth Routes

-   GET/POST /forgot-password # Password reset
-   GET /auth/{provider} # Social login redirect
-   GET /auth/{provider}/callback # Social login callback
-   POST /logout # Logout

## Authenticated Routes

-   GET /dashboard # User dashboard
-   Resource /quizzes # CRUD for quizzes
-   Resource /quizzes/{quiz}/questions # Questions management
-   POST /quizzes/generate-text # AI text generation

## Admin Routes (prefix: /admin)

-   GET /admin/dashboard # Admin dashboard
-   GET /admin/reports # Reports and analytics (NEW)
-   Resource /admin/users # User management
-   Resource /admin/quizzes # All quizzes management
-   POST /admin/ai/generate # AI management

## Profile Routes

-   POST /profile/logout-other-devices # Logout from other devices

## Security Endpoints (if API enabled)

-   GET /api/user/sessions # View active sessions
-   POST /api/user/sessions/logout # Logout specific session
