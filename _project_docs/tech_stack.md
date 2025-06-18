# Tech Stack - Juzoor

Last Updated: December 2024

## Backend

-   **Framework**: Laravel 11.x
-   **PHP**: 8.2+
-   **Database**: MySQL 8.0
-   **Cache**: File-based (default)
-   **Queue**: Sync (default)
-   **Session**: File-based

## Frontend

-   **Template Engine**: Blade
-   **CSS Framework**: Tailwind CSS 3.x
-   **JavaScript**: Alpine.js 3.x
-   **Build Tool**: Vite
-   **Icons**: Font Awesome 6.x

## Third-Party Services

-   **AI**: Anthropic Claude API (claude-3-5-sonnet)
-   **Email**: SMTP (configured in .env)
-   **Hosting**: Namecheap (shared hosting considerations)

## Development Tools

-   **IDE**: VSCode
-   **Version Control**: Git/GitHub
-   **Package Manager**: Composer (PHP), npm (JS)
-   **Local Environment**: [Your setup - XAMPP/Docker/etc]

## Key Packages

-   laravel/breeze: Authentication scaffolding
-   laravel/sanctum: API authentication
-   anthropic/claude-sdk: AI integration

## Key Packages

-   laravel/breeze: Authentication scaffolding
-   laravel/sanctum: API authentication
-   laravel/socialite: OAuth integration (NEW)
-   jenssegers/agent: Device detection (NEW)
-   anthropic/claude-sdk: AI integration

## Security Features (NEW)

-   OAuth 2.0: Google login integration
-   Device Detection: Browser, OS, location tracking
-   Rate Limiting: Built-in Laravel + custom implementation
-   Password Policies: Custom validation rules
-   Session Management: Multi-device support

## 4. Update `_project_docs/tech_stack.md`

Add these updates:

```markdown
## Frontend Build Tools

-   **Vite**: Asset bundling and hot module replacement
-   **PostCSS**: CSS processing
-   **Autoprefixer**: CSS vendor prefixes

## Authentication Packages

-   **laravel/socialite**: OAuth integration (Google)
-   **jenssegers/agent**: Device detection for login tracking

## Production Optimizations

-   Asset compilation with Vite
-   Route caching
-   Config caching
-   View caching
-   Autoloader optimization

## Deployment Considerations

-   Shared hosting compatible
-   No SSH required (deployment via FTP/scripts)
-   MySQL database
-   PHP 8.3+ required
```
