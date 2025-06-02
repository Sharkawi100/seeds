# Views Structure - جُذور (Juzoor) Project
Last Updated: December 2024

## Layout Structure
```
resources/views/
├── layouts/
│   ├── app.blade.php (main authenticated layout)
│   ├── guest.blade.php (for non-authenticated pages)
│   └── navigation.blade.php (nav component)
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── forgot-password.blade.php
│   ├── reset-password.blade.php
│   ├── verify-email.blade.php
│   └── confirm-password.blade.php
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
│   ├── take.blade.php (take quiz view)
│   └── questions/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
├── results/
│   ├── index.blade.php (list results)
│   ├── show.blade.php (detailed result with Juzoor chart)
│   └── quiz-results.blade.php
├── profile/
│   ├── edit.blade.php
│   └── partials/
│       ├── delete-user-form.blade.php
│       ├── update-password-form.blade.php
│       └── update-profile-information-form.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── users/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── quizzes/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   └── ai/
│       └── index.blade.php
├── emails/
│   └── contact-inquiry.blade.php
├── dashboard.blade.php (user dashboard with Juzoor stats)
├── welcome.blade.php (landing page with PIN entry)
├── about.blade.php
├── contact.blade.php
├── juzoor-model.blade.php (explains the educational model)
└── question-guide.blade.php

## Key Blade Components

### Custom Components
- **juzoor-chart.blade.php**: Displays the 4 roots (جَوهر، ذِهن، وَصلات، رُؤية) in a radar chart
- **PIN input system**: On welcome page for quick quiz access

### Layout Features
- **RTL Support**: All layouts support Arabic RTL
- **Multi-language**: Arabic, English, Hebrew support
- **Responsive**: Tailwind CSS for mobile-first design
- **Dark Mode**: Gradient backgrounds and modern UI

## Styling
- **CSS Framework**: Tailwind CSS
- **Custom CSS**: 
  - resources/css/app.css
  - resources/css/landing.css
- **JavaScript**: 
  - resources/js/app.js
  - resources/js/landing.js
  - Alpine.js for interactivity

## Important Blade Directives Used
- @extends('layouts.app') - For authenticated pages
- @extends('layouts.guest') - For public pages
- @section('content') - Main content area
- @push('styles') / @push('scripts') - Page-specific assets
- @auth / @guest - Authentication checks
- {{ __() }} - Translation helpers

## View Data Flow
1. **Dashboard**: Shows user's quiz statistics and Juzoor root progress
2. **Quiz Creation**: Multi-step form with AI generation options
3. **Quiz Taking**: Displays questions with passage support
4. **Results**: Shows detailed analysis with root-wise scoring
