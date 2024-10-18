# TaskNote Manager

## Description
TaskNote Manager is an application designed for managing tasks and notes efficiently. Users can create and retrieve tasks with filtering options. The tasks are ordered by priority, with "High" priority tasks displayed first, and tasks are further sorted by the maximum count of notes.

## Features
- **Task Management:** Create and retrieve tasks with notes.
- **Notes Management:** Attach multiple files to notes.
- **Filtering Options:** Filter tasks by status, due date, priority, and notes.
- **filter[status]**: Filter tasks by their status (e.g., "New", "Incomplete", "Complete").
- **filter[due_date]**: Filter tasks based on their due date.
- **filter[priority]**: Filter tasks by priority (e.g., "High", "Medium", "Low").
- **filter[notes]**: Use the value `R` to filter tasks that have at least one note attached.
- **File Attachments:** Attachments for notes will be uploaded to the `storage/app` directory by default using Laravel's file storage system.

## System Requirements
- **PHP Version:** 8.0 or higher
- **MySQL Version:** Ensure MySQL is installed and configured correctly
- **Composer:** Ensure Composer is installed on your machine.

## Installation Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/sonagarapooja/TaskNoteManager.git
   cd TaskNoteManager
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Set Up Environment Variables**
   - Copy the `.env.example` file to `.env` and update your database credentials.
   ```bash
   php artisan key:generate
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

5. **Seed the Database**
   ```bash
   php artisan db:seed
   ```

6. **Start the Development Server**
   ```bash
   php artisan serve
   ```

## API Endpoints

### Authentication
- **POST** `/register`: Register a new user.
- **POST** `/login`: Login an existing user.

### Tasks
- **GET** `/tasks`: Retrieve a list of tasks (requires authentication).
- **POST** `/tasks`: Create a new task (requires authentication).

### Authentication Middleware
All task-related endpoints require JWT authentication. Make sure to include a valid JWT token in the Authorization header for requests to the `/tasks` endpoints.

## Test Login Credentials
- **Email:** `test@example.com`
- **Password:** `TestPWD123`

## Additional Notes
- Ensure PHP and Composer are installed on your machine.
- For any questions or issues, feel free to contact me at [sonagarapooja@gmail.com].
