# Dry Redirects

A URL redirection package for the Dry PHP framework. This package allows you to manage redirects through an admin interface and automatically handle URL redirections with parameter support.

## Features

- Admin interface for managing redirects
- Support for 301 (permanent), 302 (temporary), and 404 (not found) redirects
- Parameter matching and substitution (e.g., `/product/{id}` → `/products/{id}`)
- Redirect hit tracking and logging
- Database migrations for easy setup

## Installation

### Via Composer

```bash
composer require tallieutallieu/dry-redirects
```

### Service Provider Registration

Add the service provider to your application:

```php
// In your application's service provider registration
$app->register(Tnt\Redirects\RedirectServiceProvider::class);
```

### Database Migration

Run the database migrations to create the necessary tables:

```bash
php oak migration migrate
```

## Usage
### Creating Redirects

Navigate to the admin interface at `/admin/redirects` and create redirects with:

1. **Source Path**: The URL pattern to match (e.g., `/old-path` or `/product/{id}`)
2. **Target Path**: The destination URL (e.g., `/new-path` or `/products/{id}`)
3. **Status Code**: 301 (permanent), 302 (temporary), or 404 (not found)
4. **Active**: Enable/disable the redirect

### Parameter Substitution

You can use parameters in your redirects by enclosing them in curly braces:

- Source Path: `/product/{id}`
- Target Path: `/products/{id}`

When a user visits `/product/123`, they will be redirected to `/products/123`.

### Redirect Logs

The system automatically logs all redirect hits. You can view these logs in the admin interface at `/admin/redirects/redirect-logs`.

## Under the Hood

The package works by:

1. Loading all active redirects from the database at application boot
2. Registering dynamic routes for each redirect
3. When a redirect is triggered:
   - Parameters are extracted and substituted
   - A `RouteWasHit` event is dispatched (for logging)
   - The user is redirected to the target URL with the appropriate status code

## Administration

The admin interface provides two main sections:

1. **Redirects**: Create, edit, and delete redirects
2. **Redirect Logs**: View and manage redirect hit logs

## Requirements

- tallieutallieu/dry: ^v3.1.0
- tallieutallieu/oak: dev-php8.2

## License

This package is proprietary software developed by Tallieu & Tallieu.
