# Views Structure - جُذور (Juzoor) Project

Last Updated: June 2025

## Layout Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php (main authenticated layout)
│   ├── guest.blade.php (for non-authenticated pages)
│   └── navigation.blade.php (nav component)
├── auth/
│   ├── role-selection.blade.php (NEW - role selection for login/register)
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── forgot-password.blade.php
│   ├── reset-password.blade.php
│   ├── verify-email.blade.php
│   ├── confirm-password.blade.php
│   ├── teacher/
│   │   ├── login.blade.php (NEW - teacher login)
│   │   ├── register.blade.php (NEW - teacher registration)
│   │   └── pending-approval.blade.php (NEW - waiting for admin approval)
│   └── student/
│       ├── login.blade.php (NEW - student login with PIN option)
│       └── register.blade.php (NEW - student registration)
├── components/
│   ├── application-logo.blade.php
│   ├── auth-session-status.blade.php
│   ├── danger-button.blade.php
│   ├── dropdown.blade.php
│   ├── dropdown-link.blade.php
│   ├── input-error.blade.php
│   ├── input-label.blade.php
│   ├── juzoor-chart.blade.php (custom roots visualization)
│   ├── modal.blade.php
│   ├── nav-link.blade.php
│   ├── primary-button.blade.php
│   ├── responsive-nav-link.blade.php
│   ├── secondary-button.blade.php
│   └── text-input.blade.php
├── quizzes/
│   ├── index.blade.php (list all quizzes)
│   ├── create.blade.php (create new quiz)
│   ├── show.blade.php (display quiz details)
│   ├── edit.blade.php (edit quiz)
│   ├── take.blade.php (take quiz view - UPDATED with new UI)
│   ├── guest-info.blade.php (NEW - guest name form)
│   └── questions/
│       ├── index.blade.php
│       ├── create.blade.php
│       ├── edit.blade.php
│       └── bulk-edit.blade.php (NEW - bulk editing)
├── results/
│   ├── index.blade.php (UPDATED - modern glassmorphism design)
│   ├── quiz-results.blade.php (UPDATED - Chart.js integration)
│   └── show.blade.php (detailed result with Juzoor chart)
│   └── teacher-index.blade.php (NEW - teacher results view)
├── profile/
│   ├── edit.blade.php
│   └── partials/
│       ├── delete-user-form.blade.php
│       ├── update-password-form.blade.php
│       └── update-profile-information-form.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── reports.blade.php (NEW - analytics and reports)
│   ├── users/
│   │   ├── index.blade.php (UPDATED - improved UI)
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── quizzes/
│   │   ├── index.blade.php
│   ├── edit.blade.php (UPDATED - TinyMCE fix, management actions)
│   ├── show.blade.php (UPDATED - conditional management buttons)
│   └── ai/
│       └── index.blade.php
├── emails/
│   └── contact-inquiry.blade.php
├── dashboard/
│   ├── teacher.blade.php (NEW - teacher dashboard component)
│   └── student.blade.php (NEW - student dashboard component)
├── dashboard.blade.php (user dashboard with role-based content)
├── welcome.blade.php (landing page with PIN entry)
├── about.blade.php
├── contact.blade.php
├── juzoor-model.blade.php (explains the educational model)
├── juzoor-growth.blade.php (NEW - growth model page)
├── question-guide.blade.php
├── for-teachers.blade.php (NEW - teachers landing page)
└── for-students.blade.php (NEW - students landing page)
```

## Key Updates (June 2025)

### New Landing Pages

-   **for-teachers.blade.php**: Professional landing page showcasing features for educators

    -   AI-powered quiz generation
    -   Analytics and progress tracking
    -   Multi-language support
    -   Testimonials section
    -   Clear CTAs for registration

-   **for-students.blade.php**: Fun, engaging landing page for students
    -   Gamified presentation
    -   Achievement system preview
    -   Easy-to-understand 4 roots explanation
    -   Colorful, animated design
    -   PIN entry promotion

### Enhanced Authentication Views

-   **Role-based login/registration**: Separate flows for teachers and students
-   **Teacher approval workflow**: Pending approval page while waiting for admin
-   **Student PIN login**: Quick access option for classroom use

### Dashboard Components

-   **dashboard/teacher.blade.php**: Teacher-specific stats and quick actions
-   **dashboard/student.blade.php**: Student progress and achievements

### UI/UX Improvements

-   Modern glassmorphism effects
-   Animated backgrounds
-   Better color contrast for accessibility
-   Mobile-responsive designs
-   RTL support enhanced

## Blade Component Features

### Custom Components Usage

-   **juzoor-chart.blade.php**:
    ```blade
    <x-juzoor-chart :scores="$result->scores" size="medium" />
    ```

### Layout Selection

-   Guest pages use `@extends('layouts.guest')`
-   Authenticated pages use `@extends('layouts.app')`
-   Role-specific content with `@if(Auth::user()->user_type === 'teacher')`

## Styling Approach

-   **CSS Framework**: Tailwind CSS
-   **Custom Animations**: Float, bounce, wiggle, gradient animations
-   **Color Scheme**:
    -   Teachers: Professional purple/blue gradients
    -   Students: Vibrant, playful colors
-   **Accessibility**: High contrast text, proper spacing

## JavaScript Enhancements

-   Alpine.js for interactivity
-   Confetti effects for student achievements
-   Smooth scroll behavior
-   Form validation feedback
-   Progress tracking animations
