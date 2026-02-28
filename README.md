# FreshGuide Backend — Laravel API

## Stack
- Laravel 11 (latest stable)
- MySQL
- Session-based admin auth (no Sanctum required)
- Public REST API for Android sync

---

## Initial Setup

```bash
# 1. Scaffold Laravel in this directory
composer create-project laravel/laravel .

# 2. Delete default migrations (we replace them)
rm database/migrations/*.php

# 3. Copy all files from this repo into the Laravel project (overwrite)

# 4. Configure .env
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_DATABASE=freshguide
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

```bash
# 5. Run migrations and seed
php artisan migrate --seed

# 6. Register admin middleware in bootstrap/app.php (Laravel 11)
# See: Register Middleware section below

# 7. Serve
php artisan serve
```

---

## Register Middleware (Laravel 11)

In `bootstrap/app.php`, inside `->withMiddleware(...)`:

```php
use App\Http\Middleware\EnsureIsAdmin;

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => EnsureIsAdmin::class,
    ]);
})
```

---

## Default Admin Credentials (seeded)

```
Email:    admin@freshguide.com
Password: password
```

Change immediately after first login.

---

## API Endpoints

### Public
| Method | URL | Description |
|--------|-----|-------------|
| GET | /api/sync/version | Latest published version number |
| GET | /api/sync/bootstrap | Full campus data payload |

### Admin (session auth required)
| Method | URL |
|--------|-----|
| * | /admin/buildings |
| * | /admin/floors |
| * | /admin/rooms |
| * | /admin/facilities |
| * | /admin/origins |
| * | /admin/routes |
| POST | /admin/publish |

---

## Folder Structure (custom files)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── BuildingController.php
│   │   │   ├── FacilityController.php
│   │   │   ├── FloorController.php
│   │   │   ├── OriginController.php
│   │   │   ├── PublishController.php
│   │   │   ├── RoomController.php
│   │   │   └── RouteController.php
│   │   ├── Auth/
│   │   │   └── AdminLoginController.php
│   │   └── Api/
│   │       └── SyncController.php
│   └── Middleware/
│       └── EnsureIsAdmin.php
└── Models/
    ├── Building.php
    ├── CampusRoute.php
    ├── DataVersion.php
    ├── Facility.php
    ├── Floor.php
    ├── Origin.php
    ├── Room.php
    ├── RouteStep.php
    └── User.php

database/
├── migrations/
│   ├── 2026_02_28_000001_create_users_table.php
│   ├── 2026_02_28_000002_create_buildings_table.php
│   ├── 2026_02_28_000003_create_floors_table.php
│   ├── 2026_02_28_000004_create_rooms_table.php
│   ├── 2026_02_28_000005_create_facilities_table.php
│   ├── 2026_02_28_000006_create_room_facilities_table.php
│   ├── 2026_02_28_000007_create_origins_table.php
│   ├── 2026_02_28_000008_create_routes_table.php
│   ├── 2026_02_28_000009_create_route_steps_table.php
│   └── 2026_02_28_000010_create_data_versions_table.php
└── seeders/
    ├── DatabaseSeeder.php
    └── CampusDataSeeder.php

routes/
├── api.php
└── web.php
```
