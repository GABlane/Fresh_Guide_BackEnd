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
| Room | rooms | belongs to Floor; type enum; image_url + location fields; many-to-many Facility |
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
POST   /api/admin/rooms/{id}/image    — multipart image upload
DELETE /api/admin/rooms/{id}/image   — remove room image
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
      RoomImageController.php      — upload/delete room images
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

## Agent Routing Defaults
- Building agent: gpt-5.3-codex (high)
- Planner agent: claude-sonnet-3-5v2

## AI Workflow (Claude Code ↔ Codex Bridge)

This project uses a two-agent system:
- **Claude Code** — plans, delegates, synthesizes. Talks to the user.
- **Codex** — executes. Writes code, runs commands, fixes builds.

### Bridge Folder
```
~/ai-bridge/
  inbox/    ← Claude drops TASK-*.md files here
  outbox/   ← Codex writes TASK-*.result.md files here
  archive/  ← Completed pairs (do not touch)
  status.json
```

### How to delegate a task to Codex
Claude Code writes a `TASK-NNN.md` file to `~/ai-bridge/inbox/` in this format:
```
---
task_id: TASK-001
created: <ISO timestamp>
mode: L1 | L2 | L3
priority: low | normal | high
status: pending
project: Fresh_Guide_BackEnd
project_dir: /Users/gearworxdev/Projects/Fresh Guide/Fresh_Guide_BackEnd
---

## Context
Why this task exists.

## Steps
1. Do this
2. Then this

## Files to Touch
- path/to/file.php

## Success Criteria
- php artisan route:list passes
- Feature works as described
```

### Worker commands
```bash
bridge-launch    # start the worker (auto-picks up tasks)
bridge-logs      # tail live logs
bridge-stop      # stop the worker
bridge-verify    # check status
```

### Task numbering
Check `~/ai-bridge/inbox/` and `~/ai-bridge/archive/` for the latest TASK-NNN to determine the next number.
```
