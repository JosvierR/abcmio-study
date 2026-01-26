# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

ABCmio is a Laravel 5.8-based real estate/property listing platform with React/Vue.js frontend components. The application supports multiple languages (English, Spanish, French) and includes features like property management, user authentication, credit-based premium features, and payment processing.

## Development Environment - DDEV

Este proyecto utiliza DDEV para el entorno de desarrollo local. DDEV proporciona un entorno Docker preconfigurado con PHP, MySQL, y otras herramientas necesarias.

### Comandos DDEV
```bash
# Iniciar el entorno DDEV
ddev start

# Detener el entorno
ddev stop

# Acceder al contenedor web
ddev ssh

# Ejecutar comandos dentro del contenedor
ddev exec php artisan migrate
ddev exec npm install
ddev exec composer install

# Ver logs
ddev logs

# Importar base de datos
ddev import-db --file=database.sql
```

### URLs del Proyecto
- **Aplicación**: https://abcmio.ddev.site
- **Mailpit**: https://abcmio.ddev.site:8026 (para testing de emails)
- **phpMyAdmin**: https://abcmio.ddev.site:8037

## Essential Commands

### Development Setup
```bash
# Con DDEV (recomendado)
ddev start
ddev composer install
ddev npm install

# Configuración de base de datos
ddev exec php artisan migrate
ddev exec php artisan db:seed

# Compilar assets
ddev npm run watch

# Sin DDEV (alternativa)
composer install
npm install
php artisan migrate
php artisan db:seed
php artisan serve
npm run watch
```

### Build Commands
```bash
npm run dev            # Development build
npm run production     # Production build
npm run hot            # Hot module replacement
```

### Testing
```bash
# Run all tests
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit tests/Feature/PropertyTest.php

# Run with filter
vendor/bin/phpunit --filter testPropertyCreation
```

### Database Operations
```bash
php artisan migrate:fresh --seed    # Reset database with seeds
php artisan migrate:rollback        # Rollback last migration
php artisan make:migration create_table_name    # Create new migration
```

## Architecture Overview

### Backend Structure
The application follows Laravel's MVC pattern with additional layers:

- **Controllers** (`app/Http/Controllers/`): Handle HTTP requests, organized by feature (Admin/, Auth/, Front/)
- **Models** (`app/Models/`): Eloquent models with relationships and business logic
- **Services** (`app/Services/`): Complex business logic separated from controllers
- **Repositories** (`app/Repositories/`): Data access layer for database queries
- **Policies** (`app/Policies/`): Authorization logic for resources

### Frontend Architecture
- **Blade Templates** (`resources/views/`): Server-side rendering with layouts
- **React Components** (`resources/js/components/`): Interactive UI components
- **Vue Components** (`resources/js/components/`): Mixed with React for specific features
- **Asset Pipeline**: Laravel Mix (webpack) compiles JS/Sass from `resources/` to `public/`

### Key Models and Relationships
```
User -> hasMany -> Properties
Property -> belongsTo -> Category, City, Country
Property -> hasMany -> Photos, Reports
Property -> belongsToMany -> Users (favorites)
User -> hasMany -> Credits, Orders
```

### Authentication & Authorization
- Laravel's built-in authentication with email verification
- Role-based access control (admin, agent, user)
- Policy-based authorization for resources
- Credit system for premium features

### Multi-language Support
- Localization files in `resources/lang/{locale}/`
- Language switching via `App::setLocale()`
- Database content translation handled via locale columns

### Payment Integration
- PayPal integration for credit purchases
- Order processing through `PaypalPaymentController`
- Credit system managed via `CreditController`

### Media Management
- Spatie Media Library for file uploads
- Image processing with Intervention Image
- Media stored in `storage/app/public/`
- Thumbnails generated automatically

### Development Environment
- **DDEV**: Entorno Docker preconfigurado para desarrollo local
  - PHP 7.1.3+ con extensiones necesarias
  - MySQL 5.7
  - Node.js y NPM incluidos
  - Configuración en `.ddev/config.yaml`
- Environment configuration via `.env` file
- Debug tools: Laravel Debugbar and Telescope

## Common Development Tasks

### Adding a New Feature
1. Create migration: `php artisan make:migration create_feature_table`
2. Create model: `php artisan make:model Feature`
3. Create controller: `php artisan make:controller FeatureController --resource`
4. Add routes in `routes/web.php` or `routes/api.php`
5. Create views in `resources/views/feature/`
6. Add translations in `resources/lang/*/feature.php`

### Working with Frontend Assets
1. JavaScript files go in `resources/js/`
2. Sass files go in `resources/sass/`
3. Import new JS/CSS in `resources/js/app.js` or `resources/sass/app.scss`
4. Run `npm run watch` during development
5. Components can be React or Vue.js

### Database Queries
Use repositories for complex queries:
```php
// In controllers
$properties = $this->propertyRepository->searchWithFilters($filters);
```

Use Eloquent Filter package for advanced filtering:
```php
Property::filter($request->all())->paginate();
```

### Testing Approach
- Feature tests for HTTP endpoints and user flows
- Unit tests for services and repositories
- Use database transactions for test isolation
- Mock external services (PayPal, email)
