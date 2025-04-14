# Simple PHP Routing Application

This is a simple PHP application that demonstrates basic routing, handling HTTP requests, and organizing controllers for CRUD operations. The application uses **Dotenv** for environment variable management, **PDO** for database interactions, and a custom router to handle different HTTP methods and routes.

## Features

- **Routing System**: Allows defining routes for GET, POST, PUT, and DELETE HTTP methods.
- **Controller Management**: Routes are connected to controller methods that handle business logic.
- **Environment Variables**: Uses the Dotenv library to load environment variables (e.g., database connection settings) from a `.env` file.
- **Basic CRUD**: The application supports basic CRUD (Create, Read, Update, Delete) operations for entities like users.

## Requirements

Before running the application, make sure you have the following installed:

- PHP 7.4 or higher
- Composer (for dependency management)
- A web server (Apache, Nginx, or PHP’s built-in server)

## Setup

### 1. Clone the repository:

```bash
git clone https://github.com/your-username/simple-php-routing-app.git
cd simple-php-routing-app
