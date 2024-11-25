
# Toptal RemindMe App

**Toptal RemindMe App** is a task reminder application built with a Laravel backend (using SQLite) and a React.js frontend. The app allows users to manage their reminders effectively.

---

## Stack

- **Backend**: Laravel with SQLite  
- **Frontend**: React.js  
- **Backend Language**: PHP 8.2  
- **Frontend Environment**: Node.js 18  

---

## Features

### Backend:
- RESTful API for managing reminders.
- Token-based authentication with access and refresh tokens.
- Scheduling system for automatic task reminders.
- Unit-tested endpoints.

### Frontend:
- User-friendly React.js interface.
- Real-time reminders and notifications.
- Responsive design.

---

## Backend Setup

### Prerequisites
- PHP 8.2
- Composer
- SQLite database

### Steps to Set Up

1. **Clone the repository**:
   ```bash
   git clone <backend-repo-url>
   cd backend
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Set up environment variables**:
   - Create a `.env` file at the root directory:
     ```bash
     cp .env.example .env
     ```
   - Create a `.env.testing` file for testing configurations.

4. **Run migrations**:
   ```bash
   php artisan migrate
   ```

5. **Seed the database**:
   ```bash
   php artisan db:seed --class=UserSeeder
   ```

6. **Start the server**:
   ```bash
   php artisan serve
   ```

7. **Start the scheduler**:
   ```bash
   php artisan schedule:work
   ```

8. **Run automated tests**:
   ```bash
   php artisan test
   ```

---

### Sample `.env` File
```env
APP_NAME=ToptalRemindMe
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

---

### Sample `.env.testing` File
```env
APP_NAME=ToptalRemindMe
APP_ENV=testing
APP_KEY=base64:your-testing-app-key-here
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/testing-database.sqlite

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## Frontend Setup

### Prerequisites
- Node.js 18
- npm

### Steps to Set Up

1. **Clone the repository**:
   ```bash
   git clone <frontend-repo-url>
   cd frontend
   ```

2. **Install dependencies**:
   ```bash
   npm install
   ```

3. **Start the frontend**:
   ```bash
   npm run
   ```

---

## Project Structure

### Backend
```
backend/
├── app/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   ├── seeders/
├── routes/
│   ├── api.php
│   ├── web.php
├── tests/
│   ├── Feature/
│   ├── Unit/
├── .env.example
├── artisan
└── composer.json
```

### Frontend
```
frontend/
├── public/
├── src/
│   ├── components/
│   ├── pages/
│   ├── services/
│   ├── App.js
│   ├── index.js
├── package.json
└── .env
```

---

## Testing

### Backend:
Tests are written using PHPUnit. To run the tests:
```bash
php artisan test
```

### Frontend:
React testing can be implemented using tools like Jest or React Testing Library.

---

## Notes

- Ensure you have the correct versions of PHP, Composer, Node.js, and npm.
- SQLite is used for both development and testing environments. Adjust `.env` files as needed.

---

## Contribution

Feel free to contribute by submitting issues or pull requests. Fork the repository, make your changes, and create a pull request.

---

## License

This project is licensed under the MIT License.
