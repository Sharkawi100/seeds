# Routes Summary - Juzoor

Last Updated: December 2024

## Public Routes

-   GET / # Landing page with PIN entry
-   POST /quiz/enter-pin # Enter quiz via PIN
-   GET /quiz/{quiz}/take # Take quiz (guest allowed)
-   POST /quiz/{quiz}/submit # Submit answers
-   GET /results/{result} # View results (with token)

## Auth Routes

-   GET/POST /login # User login
-   GET/POST /register # User registration
-   POST /logout # Logout
-   GET/POST /forgot-password # Password reset

## Authenticated Routes

-   GET /dashboard # User dashboard
-   Resource /quizzes # CRUD for quizzes
-   Resource /quizzes/{quiz}/questions # Questions management
-   POST /quizzes/generate-text # AI text generation

## Admin Routes (prefix: /admin)

-   GET /admin/dashboard # Admin dashboard
-   Resource /admin/users # User management
-   Resource /admin/quizzes # All quizzes management
-   POST /admin/ai/generate # AI management

## Auth Routes (Updated)

-   GET/POST /login # User login (enhanced UI)
-   GET/POST /register # User registration (multi-step)
-   POST /logout # Logout
-   GET/POST /forgot-password # Password reset
-   GET /auth/{provider} # Social login redirect (NEW)
-   GET /auth/{provider}/callback # Social login callback (NEW)

## Profile Routes (NEW)

-   POST /profile/logout-other-devices # Logout from other devices

## Security Endpoints (if API enabled)

-   GET /api/user/sessions # View active sessions
-   POST /api/user/sessions/logout # Logout specific session
