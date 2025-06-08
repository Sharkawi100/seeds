# Features & Business Logic - Juzoor

Last Updated: June 2025

## Core Features

### 1. Educational Model (جُذور)

-   4 Roots: جَوهر (Essence), ذِهن (Mind), وَصلات (Connections), رُؤية (Vision)
-   3 Depth Levels per root (Surface, Medium, Deep)
-   Holistic assessment approach
-   Balanced question distribution across all roots

### 2. Quiz Creation Modes

-   **Manual**: Teacher creates questions manually
-   **AI-Powered**: Claude generates questions automatically
-   **Hybrid**: AI generates, teacher edits and refines

### 3. Access Methods

-   **Authenticated Teachers**: Full quiz management capabilities
-   **Authenticated Students**: Track progress, save results
-   **Guest Access**: Via 6-character PIN, results stored for 7 days
-   **Public Sharing**: Direct quiz links for easy distribution

### 4. AI Integration (Claude 3.5 Sonnet)

-   Educational text generation (stories, articles, dialogues)
-   Question generation from text with root balancing
-   Automatic difficulty level adjustment
-   Support for Arabic, English, Hebrew content
-   Smart question distribution across roots and depths

### 5. Enhanced Authentication System (Updated June 2025)

-   **Multi-method Login**:
    -   Email/Password
    -   Google OAuth
    -   Facebook OAuth (ready when configured)
    -   Student PIN login (school code + ID)
-   **Role-Specific Flows**:
    -   Separate login/registration for teachers and students
    -   Teacher approval workflow (admin must approve)
    -   Student auto-approval for immediate access
-   **Security Features**:
    -   Login attempt tracking (5 attempts = 15 min lockout)
    -   Account lockout mechanism
    -   Strong password requirements
    -   Session management
    -   Impersonation for admins

### 6. Landing Pages (NEW June 2025)

-   **For Teachers Page** (`/for-teachers`):
    -   Professional design with gradient backgrounds
    -   Feature showcase (AI, analytics, multi-language)
    -   Step-by-step process explanation
    -   Testimonials section
    -   Clear CTAs for registration
-   **For Students Page** (`/for-students`):
    -   Fun, gamified design with animations
    -   Kid-friendly language and visuals
    -   Achievement system preview
    -   Simple explanation of 4 roots
    -   Emphasis on learning being fun

### 7. Dashboard Experience

-   **Teacher Dashboard**:
    -   Quiz management overview
    -   Student performance analytics
    -   Quick creation actions
    -   Recent activity tracking
    -   Teaching tips and insights
-   **Student Dashboard**:
    -   Progress visualization
    -   Achievement badges
    -   Recent quiz results
    -   Performance trends
    -   Motivational elements

### 8. Results & Analytics

-   **For Teachers**:
    -   Class-wide performance analysis
    -   Individual student tracking
    -   Root-wise strength/weakness identification
    -   Exportable reports
    -   Time-based progress tracking
-   **For Students**:
    -   Interactive radar charts
    -   Achievement unlocking
    -   Personal best tracking
    -   Improvement suggestions
    -   Shareable results

## Business Rules

### Quiz Management

-   Quizzes must have at least 1 question
-   Each question must have 2-6 answer options
-   PIN codes are 6 characters (alphanumeric), auto-generated
-   Questions can be edited until first submission
-   Quizzes can be duplicated for reuse

### User Management

-   **Teachers**:
    -   Must be approved by admin before creating quizzes
    -   Can view all results for their quizzes
    -   Can manage (edit/delete) only their own quizzes
-   **Students**:
    -   Auto-approved upon registration
    -   Can only view their own results
    -   Can use PIN or account login
-   **Admins**:
    -   Full access to all features
    -   Can impersonate users for support
    -   Manage user approvals

### AI Usage

-   Tracked per user for fair usage
-   Limits can be configured in admin panel
-   Educational content generation only
-   Automatic content filtering for appropriateness

### Guest Access

-   Results stored for 7 days
-   No personal data required (optional name)
-   Can upgrade to full account later
-   Token-based result retrieval

### Security Rules

-   Passwords: Min 8 chars, mixed case, numbers, symbols
-   Failed login lockout: 15 minutes after 5 attempts
-   Session timeout: 120 minutes
-   CSRF protection on all forms
-   XSS prevention in all outputs

## User Flows

### Teacher Journey

1. Land on `/for-teachers` → Learn about features
2. Register → Wait for admin approval
3. Create quiz (manual/AI/hybrid)
4. Share PIN with students
5. Monitor results in real-time
6. Generate reports and insights

### Student Journey

1. Land on `/for-students` → Get excited about learning
2. Enter PIN from teacher OR register account
3. Take engaging quiz
4. See immediate results with visuals
5. Track progress over time
6. Unlock achievements

### Guest Journey

1. Receive PIN from teacher
2. Enter PIN on homepage
3. Optionally provide name
4. Take quiz
5. View results (valid for 7 days)
6. Option to create account

## Technical Implementation Details

### Database Optimization

-   Indexed columns for PIN lookup
-   JSON fields for flexible data storage
-   Soft deletes for data retention
-   Efficient eager loading for relationships

### Performance Considerations

-   Cached quiz data during taking
-   Optimized for shared hosting
-   Minimal JavaScript dependencies
-   CDN for static assets

### Accessibility

-   WCAG AA compliant color contrasts
-   Keyboard navigation support
-   Screen reader friendly
-   RTL language support
-   Mobile-first responsive design

## Recent Updates (June 2025)

1. **Landing Pages**: Created engaging, role-specific landing pages
2. **Color Contrast**: Fixed accessibility issues with button text
3. **Top Spacing**: Added proper spacing in hero sections
4. **Navigation**: Improved route organization
5. **Documentation**: Comprehensive update of all project docs
6. **Admin Features**: Enhanced user management and reports
