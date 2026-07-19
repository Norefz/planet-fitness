# Planet Fitness

Planet Fitness is a Laravel-based gym management platform that connects **members**, **mentors** (personal trainers/coaches), and **admins** in one system. Members follow structured workout programs, log nutrition, and book 1-on-1 video consultations with mentors over Zoom; mentors build and manage those programs and their client roster; admins oversee the whole platform.

## Features

### For Members
- Register/login with email or Google OAuth
- Browse and enroll in workout programs, and track exercise-by-exercise progress
- Log daily nutrition against personal macro targets
- Book, view, and cancel video consultations with a mentor (Zoom meetings are created automatically)
- Manage profile details and profile photo (stored on Cloudinary)

### For Mentors
- Register/login with email or Google OAuth, then complete an onboarding profile
- Onboarding requires **admin verification** before the dashboard unlocks
- Create and manage workout programs, with reorderable exercises (with optional demo videos)
- Publish/unpublish programs
- Manage incoming consultation bookings
- View statistics on member engagement and progress
- Manage profile and certification details

### For Admins
- Separate authentication guard from members/mentors
- Manage member accounts (view, activate/deactivate)
- Review and verify mentor applications
- Oversee all workout programs (view, publish/unpublish, delete)
- Manage consultation bookings (confirm/cancel)
- View reports and platform activity logs
- System configuration settings

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.3+)
- **Frontend:** Blade templates, Tailwind CSS 4, Vite
- **Auth:** Laravel's built-in auth + [Laravel Socialite](https://laravel.com/docs/socialite) (Google OAuth)
- **Media storage:** [Cloudinary](https://cloudinary.com/) (profile photos, exercise videos)
- **Video consultations:** [Zoom API](https://developers.zoom.us/) (server-to-server OAuth)
- **Database:** SQLite by default (configurable to MySQL/Postgres via `.env`)

## Requirements

- PHP 8.3+
- Composer
- Node.js & npm
- A Zoom Server-to-Server OAuth app (for consultations)
- A Cloudinary account (for media uploads)
- A Google Cloud OAuth client (for social login)

## Getting Started

1. **Clone and install dependencies**

   ```bash
   git clone https://github.com/Norefz/planet-fitness.git
   cd planet-fitness
   composer install
   npm install
   ```

2. **Environment setup**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Then fill in the following in `.env`:

   ```
   APP_NAME="Planet Fitness"

   GOOGLE_CLIENT_ID=
   GOOGLE_CLIENT_SECRET=
   GOOGLE_REDIRECT_URI=

   ZOOM_ACCOUNT_ID=
   ZOOM_CLIENT_ID=
   ZOOM_CLIENT_SECRET=

   CLOUDINARY_CLOUD_NAME=
   CLOUDINARY_API_KEY=
   CLOUDINARY_API_SECRET=
   ```

3. **Database**

   The app defaults to SQLite. Create the database file and run migrations:

   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

4. **Create an admin account**

   There's no public admin registration page — admins are created from the terminal:

   ```bash
   php artisan admin:create
   ```

   This walks you through setting a name, email, password, and title, then prints the admin login URL.

5. **(Optional) Seed demo data**

   ```bash
   php artisan db:seed
   ```

6. **Run the app**

   During development, this single command runs the PHP server, queue listener, log watcher, and Vite dev server together:

   ```bash
   composer run dev
   ```

   Or run the pieces individually:

   ```bash
   php artisan serve
   npm run dev
   ```

## Testing

```bash
composer test
```

## Key Routes

| Area   | Prefix     | Notes                                              |
|--------|------------|-----------------------------------------------------|
| Public | `/`        | Landing page, login/register selection              |
| Member | `/member`  | Programs, consultations, nutrition log, profile      |
| Mentor | `/mentor`  | Onboarding, dashboard, programs, bookings, stats     |
| Admin  | `/admin`   | Separate guard; members, mentors, programs, reports  |

## Project Structure Highlights

```
app/
  Http/Controllers/
    Admin/      # Admin-only controllers
    Auth/       # Member, mentor & Google OAuth auth
    Member/     # Programs, bookings, nutrition, profile
    Mentor/     # Programs, exercises, bookings, statistics
  Models/       # Member, Mentor, SuperAdmin, WorkoutProgram, Booking, MealLog, ...
  Services/
    CloudinaryService.php   # Media upload/delete helpers
    ZoomService.php         # Zoom meeting creation via Server-to-Server OAuth
  Observers/    # Activity/audit logging for key models
resources/views/
  admin/ mentor/ member/    # Role-specific Blade views
  components/               # Shared Blade components
database/migrations/        # Schema for users, members, mentors, programs, bookings, etc.
routes/
  web.php     # Public, member & mentor routes
  admin.php   # Admin routes
```

## License

This project is built on the [Laravel](https://laravel.com) framework, which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
