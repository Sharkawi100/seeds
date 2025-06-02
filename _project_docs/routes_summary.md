# Routes Summary - Juzoor
Last Updated: December 2024

## Public Routes
- GET  /                    # Landing page with PIN entry
- POST /quiz/enter-pin      # Enter quiz via PIN
- GET  /quiz/{quiz}/take    # Take quiz (guest allowed)
- POST /quiz/{quiz}/submit  # Submit answers
- GET  /results/{result}    # View results (with token)

## Auth Routes  
- GET/POST /login          # User login
- GET/POST /register       # User registration
- POST     /logout         # Logout
- GET/POST /forgot-password # Password reset

## Authenticated Routes
- GET  /dashboard          # User dashboard
- Resource /quizzes        # CRUD for quizzes
- Resource /quizzes/{quiz}/questions  # Questions management
- POST /quizzes/generate-text        # AI text generation

## Admin Routes (prefix: /admin)
- GET  /admin/dashboard    # Admin dashboard
- Resource /admin/users    # User management
- Resource /admin/quizzes  # All quizzes management
- POST /admin/ai/generate  # AI management
