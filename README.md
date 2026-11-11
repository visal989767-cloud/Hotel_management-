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

`vercel.json` configures the Laravel app as a Vercel Container service and
routes requests to the included `Dockerfile.vercel`. This avoids Vercel treating
the project as a static PHP site and expecting a generated `public` output
directory. Set the Vercel project's Root Directory to the repository root (the
directory containing `composer.json`, `vercel.json`, and `Dockerfile.vercel`).
Do not set a static Output Directory such as `public`.

Before deploying, create an external MySQL database and set these Production
environment variables in Vercel:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` (generate with `php artisan key:generate --show`; keep it secret)
- `APP_URL` (the production Vercel URL)
- `LOG_CHANNEL=stderr` (send Laravel logs to Vercel's runtime logs)
- `SESSION_DRIVER=file`
- `CACHE_STORE=file`
- `QUEUE_CONNECTION=sync`
- `DB_CONNECTION=mysql`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`

Run `php artisan migrate --force` against that database before serving traffic.
Do not run migrations as part of container startup; Vercel may start multiple
instances. Set the three file/sync driver variables above explicitly, even if
you copied environment variables from a local `.env` file; database-backed
sessions or cache will fail if their tables are missing. `SESSION_DRIVER`,
`CACHE_STORE`, and `QUEUE_CONNECTION` default to
`file`, `file`, and `sync`, so the login page does not require session,
cache, or job tables just to render. Keep these defaults unless you have run
the Laravel migrations and need database-backed state across container
instances; set `SESSION_DRIVER=database` and `CACHE_STORE=database` only then.
The application still requires the production MySQL variables and migrations
for users, hotel records, and site settings. All CRUD, login, dashboard, and
settings data use this same database. Do not run the demo database seeder on a public
production deployment; it creates a known demo admin account. The container's
local filesystem is ephemeral; use external session/cache storage and object
storage if the app later needs durable shared state or user-uploaded files.

## Deploy to Oracle Cloud Always Free

This project can run on an Oracle Cloud Infrastructure (OCI) Compute VM using
the included Docker Compose and Caddy setup. The app expects a reachable MySQL
database; the Compose file does not create one.

1. In OCI, create an Ubuntu Server VM in your tenancy's **home region**. The
   Always Free allowance for Ampere A1 is up to 2 OCPUs and 12 GB of memory
   total; select an A1 Flex VM within that allowance. A1 capacity can be
   temporarily unavailable in your home region. See Oracle's
   [Always Free resource limits](https://docs.oracle.com/en-us/iaas/Content/FreeTier/freetier_topic-Always_Free_Resources.htm).
2. Give the VM a public IPv4 address and configure its VCN with internet
   connectivity (an internet gateway, a public subnet, and a route to the
   internet). In the subnet's security list or the VM's network security group,
   allow inbound TCP 80 and 443 from `0.0.0.0/0`; allow TCP 22 only from your
   own public IP. Caddy also publishes UDP 443 for HTTP/3, which is optional.
   On Ubuntu, allow SSH before enabling UFW, then allow the web ports:

   ```sh
   sudo ufw allow OpenSSH
   sudo ufw allow 80/tcp
   sudo ufw allow 443/tcp
   sudo ufw allow 443/udp # optional, for HTTP/3
   sudo ufw enable
   ```

   See OCI's
   [security rules documentation](https://docs.oracle.com/en-us/iaas/Content/Network/Concepts/securityrules.htm).
3. If using a domain, point its A record to the VM's public IP. Install Docker
   Engine and the Docker Compose plugin on the VM using Docker's
   [Ubuntu installation instructions](https://docs.docker.com/engine/install/ubuntu/).
4. Copy or clone this project to the VM, then enter the directory containing
   `docker-compose.yml`. Create `.env` from `.env.example` and set:
   `APP_ENV=production`, `APP_DEBUG=false`, a unique `APP_KEY`, `APP_URL`,
   `LOG_CHANNEL=stderr`, and the production MySQL `DB_*` values. Generate the
   key with `php artisan key:generate --show` in a local project checkout and
   copy its output into `.env`. Keep `.env` private and out of source control.
   For a domain, set `APP_DOMAIN` to the
   domain only (for example, `hotel.example.com`) and `APP_URL` to
   `https://hotel.example.com`. For IP-only testing, use `APP_DOMAIN=:80` and
   `APP_URL=http://<server-ip>`; this does not provide HTTPS.
5. Ensure the MySQL database exists and accepts connections from the app VM.
   Restrict database access to the app host/network; do not expose MySQL to the
   public internet. The Compose environment uses database-backed sessions,
   cache, and queue, so their tables must be created by the migrations.
6. Build and start the services, then migrate once:

   ```sh
   docker compose build
   docker compose up -d
   docker compose exec app php artisan migrate --force
   ```

   Do not run the demo seeder on a public deployment; it creates a known demo
   admin account. Caddy obtains and renews HTTPS certificates automatically
   when `APP_DOMAIN` is a public domain and DNS and ports 80/443 are configured
   correctly.

Always Free compute is subject to Oracle's resource and capacity limits, and
Oracle may reclaim idle Always Free compute instances. Keep independent
database backups; the Compose storage volume only persists Laravel's local
storage on the VM. Do not store irreplaceable uploads there without a separate
backup or object-storage plan.

## Deploy to an always-on VPS

The VPS must have Docker Engine and the Docker Compose plugin installed, and
your MySQL server must accept connections from the VPS. Point your domain's DNS
to the VPS and allow inbound TCP ports 80 and 443. Caddy obtains and renews
HTTPS certificates automatically when `APP_DOMAIN` is a public domain name.

1. Copy the project to the VPS and enter the directory containing
   `docker-compose.yml`.
2. Create the production environment file from `.env.example`. Set at least
   `APP_ENV=production`, `APP_DEBUG=false`, a generated `APP_KEY`, `APP_URL`,
   `LOG_CHANNEL=stderr`, and the production MySQL `DB_*` values. Keep this file
   private and do not commit it. Generate a key with
   `php artisan key:generate --show` and set its output as `APP_KEY`. For the
   Compose deployment, `.env` selects database sessions, cache, and queue, so
   their tables must exist after migrations; do not use the demo seeder on a
   public deployment.
3. Set `APP_DOMAIN` in `.env` to the domain name only (for example,
   `hotel.example.com`). For temporary HTTP-only access by server IP, set
   `APP_DOMAIN=:80` and `APP_URL=http://<server-ip>`.
4. Build and start the services:

   ```sh
   docker compose build
   docker compose up -d
   ```

5. Apply database migrations once, after confirming the database settings:

   ```sh
   docker compose exec app php artisan migrate --force
   ```

The app and Caddy restart automatically if their containers stop. The VPS and
database still need provider-level backups and monitoring for reliable
availability. The `laravel_storage` volume persists Laravel storage across
container replacements; configure external object storage if you run multiple
app instances or need durable user-uploaded files.
