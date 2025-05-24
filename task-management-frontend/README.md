## Task Management - Vue 3 Frontend

### Setup

#### 1. Install dependencies:

```bash
npm install  
```
#### 3. Config backend url at nuxt.config.js and start the development server:

```bash
npm run dev  
```

### Architecture

> Vue 3 with Composition API for component logic.
>
> Nuxt 3 for streamlined server-side rendering and routing.
>
> Pinia for centralized state management, handling user authentication, tasks, and categories.
>
> Inertia.js for seamless communication between the frontend and Laravel backend.
>
> VeeValidate for robust form validation on the client side.

### Features

#### User Authentication:

- Registration

- Login

- Logout

- Token stored and used for authenticated API requests

#### Task Management:

- Create task

- List tasks

- Filter tasks by category

- Search tasks by title or description

#### Category Management:

- Categories loaded into the Pinia store

- Used for task filtering

#### Reactive UI:

- State-driven updates through Pinia

- Real-time feedback on task filtering and searching

#### Responsive Design:

- Mobile-friendly and adaptable layout

- Reusable components with Composition API

### Authentication Middleware
> Route middleware checks for JWT token presence before allowing access to protected views.
>
>Esures secure navigation throughout the frontend.

### Communication with Backend
> All API requests handled through composables for modular and reusable logic.
>
> Inertia.js used for SSR and page transitions, providing a SPA-like experience.

### Notes
> Centralized state ensures consistent data across components.
> 
> Modular architecture with composables, stores, and middleware for clean and maintainable codebase.
>
> Backend must be running to interact with the API endpoints.