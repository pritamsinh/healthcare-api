# Appointment Management System

This is a simple appointment management system built with PHP Laravel framework. It allows users to create, list, cancel, and complete appointments with healthcare professionals.

## Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/pritamsinh/healthcare-api.git
    ```

2. **Install dependencies:**
    ```bash
    composer install
    ```

3. **Run migrations to set up the database schema:**
    ```bash
    php artisan migrate
    ```

4. **Seed the database with initial data, including healthcare professionals:**
    ```bash
    php artisan db:seed --class=HealthcareProfessionalSeeder
    ```

## Usage

### Running the Application

You can run the application locally using the built-in PHP server:

```bash
php artisan serve
```


## API Documentation

Please refer to the API.doc file located in the root directory for API documentation.