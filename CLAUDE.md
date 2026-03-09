# FreshGuide Backend — Claude Context

## Project
Campus navigation app backend. Laravel 11 API-only. No Blade views, no web admin dashboard.

## Stack
| Layer | Tech |
|-------|------|
| Framework | Laravel 11 |
| Auth | Laravel Sanctum (token-based) |
| DB (prod) | MySQL |
| DB (local dev) | SQLite (`laravel/database/database.sqlite`) |
| API | JSON only |

## Root path
```
/home/john/projects/AndroidStudioProjects/Fresh_Guide_BackEnd/
  laravel/          ← Laravel app root
    app/
    config/
    database/
    routes/
    docs/API.md     ← API reference
```

## Auth Model
| Role | How they log in | Token type |
|------|----------------|------------|
| `user` | Student ID (`^\d{8}-(S\|N\|C)$`) | Sanctum token |
| `admin` | Email + password | Sanctum token |

- Admins must be **online** to use admin features
- Regular users can use offline-synced data after initial token auth
- Student campus code extracted from last char of student_id: S/N/C

## Key Models
| Model | Table | Notes |
|-------|-------|-------|
| User | users | role: admin\|user; student_id nullable; password nullable for students |
| Building | buildings | code unique |
| Floor | floors | belongs to Building |
| Room | rooms | belongs to Floor; type enum; many-to-many Facility |
| Facility | facilities | name + icon |
| Origin | origins | navigation start point (entrance/lobby etc) |
| CampusRoute | routes | origin → destination room |
| RouteStep | route_steps | ordered steps with direction + landmark |
| DataVersion | data_versions | published versions for sync tracking |

## Route Groups
```
# Public
POST   /api/register
POST   /api/admin/login
GET    /api/sync/version
GET    /api/sync/bootstrap

# Authenticated (any token)
POST   /api/logout
GET    /api/me
GET    /api/rooms
GET    /api/rooms/{id}
GET    /api/origins
GET    /api/routes/{roomId}?origin_id=

# Admin only
POST   /api/admin/logout
apiResource /api/admin/buildings
apiResource /api/admin/floors
apiResource /api/admin/rooms
apiResource /api/admin/facilities
apiResource /api/admin/origins
apiResource /api/admin/routes
POST   /api/admin/publish
```

## Controller Map
```
app/Http/Controllers/
  Api/
    Auth/
      StudentAuthController.php  — register, me, logout
      AdminAuthController.php    — login, logout
    Admin/
      BuildingController.php
      FloorController.php
      RoomController.php
      FacilityController.php
      OriginController.php
      RouteController.php
      PublishController.php
    User/
      RoomController.php
      OriginController.php
      RouteController.php
    SyncController.php
  Middleware/
    EnsureAdminRole.php          — checks role=admin, returns 403 JSON
```

## Response Envelope
```json
{ "success": true,  "data": {},   "error": null }
{ "success": false, "data": null, "error": "message" }
```

## Local Dev Commands
```bash
cd laravel/
php artisan migrate
php artisan serve
php artisan route:list --path=api
```

## Architecture Decisions Log
| Decision | Reason |
|----------|--------|
| Removed Blade admin dashboard | Admin management moved to Android app |
| Removed editor/viewer roles | Simplified to admin + user |
| Added Sanctum token auth | Mobile-first; sessions don't work for Android |
| Admin is online-only | Avoids conflict resolution complexity |
| Student ID only (no password) | Students access public campus data only; no sensitive PII |
| Buildings/Floors managed via API | Admin needs full control from mobile |

## Student ID Validation
```
Regex: ^\d{8}-(S|N|C)$
Examples: 20230054-S, 20240001-N, 20230099-C
Campus codes: S=main, N=north, C=central
```
