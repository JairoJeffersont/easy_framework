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
````

### 2. Install dependencies:

Run the following command to install required PHP libraries using Composer:

```bash
composer install
````

### 3. Set up environment variables:

Edit the .env file to set up your environment variables, including database connection settings:

````
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_NAME=my_database
DB_USER=root
DB_PASS=password
DB_CHARSET=utf8mb4
````

### 4. Create the database:

Ensure your MySQL server is running and create the database specified in your .env file.

```bash
CREATE DATABASE my_database;
````

### 5. Start the development server:

```bash
php -S localhost:8000 -t public
```

How It Works
Routing: The application uses a custom Router class to handle different HTTP methods (GET, POST, PUT, DELETE). Each route is mapped to a controller action. For example, the route GET /users maps to the index method in the UserController.

Controller: The controllers contain the business logic for handling requests. For instance, the UserController contains methods like index (to list users) and store (to create a new user).

Request Handling: The Request class captures incoming HTTP requests and retrieves important details like URI, HTTP method, and input data.

Response: The Response class formats and sends JSON responses, including success and error messages with appropriate HTTP status codes.






