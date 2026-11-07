# Hotel Management System (Laravel 13)

Final project: Login/Register, relational migrations + models, CRUD with search & filter.

## Database (relational)
| Table | PK | FK |
|---|---|---|
| `room_types` | `room_type_id` | – |
| `rooms` | `room_id` | `room_type_id` → room_types |
| `guests` | `guest_id` | – |
| `bookings` | `booking_id` | `guest_id` → guests, `room_id` → rooms |

Eloquent relations: RoomType hasMany Room · Room belongsTo RoomType, hasMany Booking · Guest hasMany Booking · Booking belongsTo Guest & Room.

## Setup (PHP 8.3+, Composer, MySQL)
```bash
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
# create an empty MySQL database named: hotel_management  (edit DB_* in .env if needed)
php artisan migrate --seed
php artisan serve
```
Open http://127.0.0.1:8000

**Demo login:** `admin@gmail.com` / `123456` (or use the Register page)

Opening the root URL (`/`) always takes you to the login page. If a browser session is already signed in, it is signed out first.

## Features
- Login & Register (`resources/views/login.blade.php`, `register.blade.php`), logout, auth-protected routes
- CRUD: Room Types, Rooms, Guests, Bookings
- Search by name + filters (type, status, gender, capacity), pagination
- Rooms/Bookings list shows `type_name` from the related `room_types` table
- Booking: auto total price, double-booking prevention, room status sync
- Delete protection when records are still referenced

## Push to GitHub
```bash
git init && git add . && git commit -m "Hotel Management System - Laravel 13"
git branch -M main
git remote add origin https://github.com/<your-username>/hotel-management.git
git push -u origin main
```

## Deploy to Vercel

Vercel's Container runtime can run the Laravel app using the included
`Dockerfile.vercel`, which selects the Container preset instead of Vite's static
output detection. Deploy the repository root (the directory containing
`composer.json`).

Before deploying, create an external MySQL database and set these Production
environment variables in Vercel:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` (generate with `php artisan key:generate --show`; keep it secret)
- `APP_URL` (the production Vercel URL)
- `LOG_CHANNEL=stderr` (send Laravel logs to Vercel's runtime logs)
- `DB_CONNECTION=mysql`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`

Run `php artisan migrate --force` against that database before serving traffic.
Do not run migrations as part of container startup; Vercel may start multiple
instances. `SESSION_DRIVER`, `CACHE_STORE`, and `QUEUE_CONNECTION` default to
database, so keep the Laravel database migrations applied. Do not run the demo
database seeder on a public production deployment; it creates a known demo
admin account. The container's local filesystem is ephemeral; use external
object storage if the app later needs persistent user-uploaded files.
