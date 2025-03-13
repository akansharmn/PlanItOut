
# PlanItOut

A web application where users can plan weekly meals for their entire family.

## Features

- User authentication (register, login, logout)
- Create and manage recipes
- Plan meals for the week
- View recent recipes and upcoming meals

## Technology Stack

- PHP 8.1
- PostgreSQL
- Bootstrap for UI
- HTMX for dynamic page updates

## Installation

### Using Docker

```bash
# Clone the repository
git clone <repository-url>
cd planitout

# Build and start the containers
docker-compose up -d
```

### Manual Setup

1. Ensure PHP 8.1+ and PostgreSQL are installed
2. Clone the repository
3. Run `php init_db.php` to initialize the database
4. Start the server with `php -S 0.0.0.0:8080 api.php`

## API Endpoints

- `/register` - Create a new user account
- `/login` - Authenticate a user
- `/logout` - End a user session
- `/home` - Main dashboard (public)
- `/recipes` - View all recipes
- `/recipe-details` - View a specific recipe

## Development

The application follows an MVC-like structure:
- `src/api/` - API endpoints and view templates
- `src/auth/` - Authentication system
- `src/db_scripts/` - Database initialization scripts
- `src/templates/` - Reusable UI components

## Running on Replit

This project is configured to run on Replit. Use the "Run" button or execute:
```bash
php -S 0.0.0.0:8080 api.php
```
