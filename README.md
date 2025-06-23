
# PlanItOut

A web application where users can plan the weekly meals for their entire family. It will allow them to
-  Create entries for each individual in the family and curate a list of their choice of food for different meals.
- Save the recipe, ingredient list, preparation steps for every food item.
- While planning meals for the week, choose from the preference list a menu item for every family member for all the meals of the week.
- Get an ingredient list with quantities specified for an entire week to keep handy while ordering grocery for the week.
- Get a list of tasks which can be done as a preparation for the meal of next day.


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
