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
