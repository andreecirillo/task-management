# Task Management API - Laravel Backend

## Setup

### 1. Install dependencies:

```bash
composer install
```

### 2. Copy the .env.example file to .env and update the variables as needed.

### 3. Run migrations and seeders:

```bash
php artisan migrate --seed
```

### 4. Start the development server:

```bash
php artisan serve
```

## Authentication

> JWT authentication implemented for securing protected routes.
>
> Login returns an access token to be used in subsequent API requests.

## API Documentation

### Generated using L5-Swagger. To update the documentation:

```bash
php artisan l5-swagger:generate
```

### Access it at: http://localhost:8000/api/documentation

## Architecture

> Laravel 12 with Eloquent ORM for database operations using PostgreSQL 17 database.
>
> RESTful routes with Controllers and Form Requests for validation.
>
> Resourceful structure, with clear separation of concerns.
>
> JWT for stateless authentication.
>
> Swagger annotations for comprehensive API documentation.

## Data Structure

-   **User**: handles authentication and task ownership.

-   **Task**: associated with a user and optionally a category.

-   **Category**: seeded data, linked directly to tasks.

## Design Decisions

> Categories: seeded in the database, simple fixed dataset, no need for dynamic creation or optimization.
>
> Query optimization: not applied due to simplicity and small dataset size, standard Eloquent queries used.
>
> Validation: handled via Laravel’s built-in validation system through Form Requests.

## Features

-   User registration, login, logout, and update.

-   CRUD operations for tasks.

-   Filter tasks by category ID or search by title/description.

-   API fully documented with Swagger.

## Notes

-   No asset compilation required for the backend.

-   Code adheres to Laravel conventions with clean organization and structure.
