# Task Management Solution

This solution consists of two main projects:

- **Laravel Backend**: Provides the REST API for task management, authentication, and data persistence.
- **Vue 3 Frontend**: Offers a responsive and reactive user interface for interacting with the backend API.

## Project Structure

- **/task-management-backend** → Laravel 12 API with JWT authentication and Swagger documentation.
- **/task-management-frontend** → Vue 3 + Nuxt 3 application with Inertia.js, Pinia, and VeeValidate.

## Features Overview

### Backend (Laravel)

- User authentication (Registration, Login, Logout) secured with **JWT**.
- CRUD operations for **Tasks**.
- Filter tasks by **Category** and search by **Title/Description**.
- API documentation generated via **L5-Swagger**.
- Clean architecture with **Controllers**, **Form Requests**, and **Eloquent** relationships.

### Frontend (Vue 3)

- Responsive UI built with **Nuxt 3** and **Composition API**.
- State management handled by **Pinia** for users, tasks, and categories.
- Form validation via **VeeValidate**.
- **Inertia.js** for seamless backend communication.
- Authentication middleware to manage JWT token flow.
- Modular code with **Composables** and reusable components.

## Notes

- The backend must be running for the frontend to properly communicate via Inertia.js.

- Authentication is managed via JWT tokens stored client-side.

- Categories are pre-seeded in the backend and used for task filtering.

- Code adheres to clean architectural patterns for maintainability and scalability.

## Documentation

- **Backend API**: Automatically generated with Swagger.

- **Frontend**: Modular components and centralized state for predictable and reactive behavior.
