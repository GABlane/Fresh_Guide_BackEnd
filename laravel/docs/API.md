# FreshGuide API Documentation

Laravel 11 + Sanctum — API-only backend for campus navigation.

**Base URL:** `https://<host>/api`

---

## Table of Contents

1. [Authentication Overview](#authentication-overview)
2. [Response Format](#response-format)
3. [Error Codes](#error-codes)
4. [Public Endpoints](#public-endpoints)
5. [Authenticated Endpoints](#authenticated-endpoints)
6. [Admin Endpoints](#admin-endpoints)
7. [Data Models](#data-models)
8. [Sync Strategy](#sync-strategy)

---

## Authentication Overview

Two separate auth flows share the same token format.

| Actor | Method | Credential |
|-------|--------|------------|
| Student/User | `POST /api/register` | Student ID |
| Admin | `POST /api/admin/login` | Email + password |

All protected endpoints require the token in the `Authorization` header:

```
Authorization: Bearer {token}
```

### Student ID Format

```
^\d{8}-(S|N|C)$
```

Examples: `20230054-S`, `20221099-N`

| Suffix | Campus |
|--------|--------|
| `S` | Main campus |
| `N` | North campus |
| `C` | Central campus |

---

## Response Format

All responses use a consistent envelope — check `success` first.

**Success:**
```json
{
  "success": true,
  "data": {},
  "error": null
}
```

**Error:**
```json
{
  "success": false,
  "data": null,
  "error": "Human-readable error message"
}
```

**Paginated (where applicable):**
```json
{
  "success": true,
  "data": [],
  "error": null,
  "meta": {
    "total": 100,
    "page": 1,
    "limit": 20
  }
}
```

---

## Error Codes

| HTTP Status | Meaning |
|-------------|---------|
| `200` | Success |
| `201` | Created |
| `400` | Bad request / validation failure |
| `401` | Missing or invalid token |
| `403` | Token valid but insufficient role (not admin) |
| `404` | Resource not found |
| `422` | Unprocessable entity (field-level validation errors) |
| `500` | Server error |

---

## Public Endpoints

No token required.

---

### POST /api/register

Register a new user with a Student ID. Returns a Sanctum token.

**Request body:**

```json
{
  "student_id": "20230054-S",
  "name": "Juan dela Cruz"
}
```

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `student_id` | string | yes | Must match `^\d{8}-(S\|N\|C)$` |
| `name` | string | no | Display name |

**Response `200`:**

```json
{
  "success": true,
  "data": {
    "token": "1|abc123...",
    "user": {
      "id": 1,
      "student_id": "20230054-S",
      "name": "Juan dela Cruz",
      "created_at": "2026-03-10T08:00:00Z"
    }
  },
  "error": null
}
```

**Response `422` — invalid student ID:**

```json
{
  "success": false,
  "data": null,
  "error": "The student_id format is invalid."
}
```

---

### POST /api/admin/login

Admin login with email and password.

**Request body:**

```json
{
  "email": "admin@freshguide.edu",
  "password": "secret"
}
```

| Field | Type | Required |
|-------|------|----------|
| `email` | string | yes |
| `password` | string | yes |

**Response `200`:**

```json
{
  "success": true,
  "data": {
    "token": "2|xyz789...",
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@freshguide.edu",
      "role": "admin"
    }
  },
  "error": null
}
```

**Response `401` — wrong credentials:**

```json
{
  "success": false,
  "data": null,
  "error": "Invalid credentials."
}
```

---

### GET /api/sync/version

Returns the current published data version. Use this to check whether a local sync is stale before fetching bootstrap data.

**Response `200`:**

```json
{
  "success": true,
  "data": {
    "version": 14
  },
  "error": null
}
```

---

### GET /api/sync/bootstrap

Returns the full campus dataset in a single request. Call this on first launch and whenever the version has changed.

**Response `200`:**

```json
{
  "success": true,
  "data": {
    "buildings": [
      {
        "id": 1,
        "name": "Engineering Building",
        "code": "EB",
        "description": "Main engineering block"
      }
    ],
    "floors": [
      {
        "id": 1,
        "building_id": 1,
        "number": 1,
        "name": "Ground Floor"
      }
    ],
    "rooms": [
      {
        "id": 1,
        "floor_id": 1,
        "name": "Room 101",
        "code": "EB-101",
        "type": "classroom",
        "description": "Lecture hall",
        "facilities": [
          { "id": 2, "name": "Projector", "icon": "projector" }
        ]
      }
    ],
    "facilities": [
      { "id": 1, "name": "WiFi", "icon": "wifi" },
      { "id": 2, "name": "Projector", "icon": "projector" }
    ],
    "origins": [
      {
        "id": 1,
        "name": "Main Gate",
        "code": "MG",
        "description": "Primary campus entrance"
      }
    ],
    "routes": [
      {
        "id": 1,
        "origin_id": 1,
        "destination_room_id": 1,
        "name": "Main Gate to EB-101",
        "description": null,
        "steps": [
          {
            "order": 1,
            "instruction": "Walk straight past the guard post.",
            "direction": "straight",
            "landmark": "Guard post"
          },
          {
            "order": 2,
            "instruction": "Turn left at the fountain.",
            "direction": "left",
            "landmark": "Fountain"
          }
        ]
      }
    ]
  },
  "error": null
}
```

---

## Authenticated Endpoints

Require `Authorization: Bearer {token}`. Valid for both student tokens and admin tokens.

---

### POST /api/logout

Revokes the current token.

**Response `200`:**

```json
{
  "success": true,
  "data": { "message": "Logged out successfully." },
  "error": null
}
```

---

### GET /api/me

Returns the currently authenticated user.

**Response `200` (student):**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "student_id": "20230054-S",
    "name": "Juan dela Cruz",
    "role": "user",
    "created_at": "2026-03-10T08:00:00Z"
  },
  "error": null
}
```

**Response `200` (admin):**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@freshguide.edu",
    "role": "admin"
  },
  "error": null
}
```

---

### GET /api/rooms

List all rooms. Each room includes its floor (with building) and attached facilities.

**Response `200`:**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Room 101",
      "code": "EB-101",
      "type": "classroom",
      "description": "Lecture hall",
      "floor": {
        "id": 1,
        "number": 1,
        "name": "Ground Floor",
        "building": {
          "id": 1,
          "name": "Engineering Building",
          "code": "EB"
        }
      },
      "facilities": [
        { "id": 1, "name": "WiFi", "icon": "wifi" }
      ]
    }
  ],
  "error": null
}
```

---

### GET /api/rooms/{id}

Returns a single room with the same shape as the list item above.

**Response `404`:**

```json
{
  "success": false,
  "data": null,
  "error": "Room not found."
}
```

---

### GET /api/origins

List all navigation origins (starting points for routes).

**Response `200`:**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Main Gate",
      "code": "MG",
      "description": "Primary campus entrance"
    }
  ],
  "error": null
}
```

---

### GET /api/routes/{roomId}?origin_id={id}

Returns the route from a given origin to a destination room, including all steps.

| Parameter | Location | Required | Type |
|-----------|----------|----------|------|
| `roomId` | path | yes | integer |
| `origin_id` | query | yes | integer |

**Response `200`:**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "origin_id": 1,
    "destination_room_id": 4,
    "name": "Main Gate to EB-101",
    "description": null,
    "steps": [
      {
        "order": 1,
        "instruction": "Walk straight past the guard post.",
        "direction": "straight",
        "landmark": "Guard post"
      },
      {
        "order": 2,
        "instruction": "Turn left at the fountain.",
        "direction": "left",
        "landmark": "Fountain"
      }
    ]
  },
  "error": null
}
```

**Response `404` — no route exists for this origin + room combination:**

```json
{
  "success": false,
  "data": null,
  "error": "Route not found."
}
```

**Direction values:** `straight` | `left` | `right` | `up` | `down`

---

## Admin Endpoints

Require `Authorization: Bearer {token}` where the token belongs to a user with `role = admin`. All write operations require an online connection — admin functionality is online-only.

Returns `403` if token is valid but not admin.

---

### POST /api/admin/logout

Revokes the admin token.

**Response `200`:**

```json
{
  "success": true,
  "data": { "message": "Logged out successfully." },
  "error": null
}
```

---

### Buildings

#### GET /api/admin/buildings

List all buildings.

**Response `200`:**

```json
{
  "success": true,
  "data": [
    { "id": 1, "name": "Engineering Building", "code": "EB", "description": "Main engineering block" }
  ],
  "error": null
}
```

#### POST /api/admin/buildings

Create a building.

**Request body:**

```json
{
  "name": "Science Building",
  "code": "SB",
  "description": "Natural sciences block"
}
```

| Field | Type | Required |
|-------|------|----------|
| `name` | string | yes |
| `code` | string | yes |
| `description` | string | no |

**Response `201`:**

```json
{
  "success": true,
  "data": { "id": 2, "name": "Science Building", "code": "SB", "description": "Natural sciences block" },
  "error": null
}
```

#### GET /api/admin/buildings/{id}

Returns a single building.

#### PUT/PATCH /api/admin/buildings/{id}

Update a building. Accepts the same fields as POST; PATCH allows partial updates.

#### DELETE /api/admin/buildings/{id}

Delete a building.

**Response `200`:**

```json
{ "success": true, "data": { "message": "Deleted." }, "error": null }
```

---

### Floors

#### GET /api/admin/floors

List all floors.

**Response `200`:**

```json
{
  "success": true,
  "data": [
    { "id": 1, "building_id": 1, "number": 1, "name": "Ground Floor" }
  ],
  "error": null
}
```

#### POST /api/admin/floors

| Field | Type | Required |
|-------|------|----------|
| `building_id` | integer | yes |
| `number` | integer | yes |
| `name` | string | yes |

**Request body:**

```json
{
  "building_id": 1,
  "number": 2,
  "name": "Second Floor"
}
```

#### GET /api/admin/floors/{id}
#### PUT/PATCH /api/admin/floors/{id}
#### DELETE /api/admin/floors/{id}

Same pattern as buildings.

---

### Rooms

#### GET /api/admin/rooms

List all rooms.

#### POST /api/admin/rooms

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `floor_id` | integer | yes | |
| `name` | string | yes | |
| `code` | string | yes | |
| `type` | string | yes | `classroom`, `office`, `lab`, `restroom`, `other` |
| `description` | string | no | |
| `facilities` | integer[] | no | Array of facility IDs |

**Request body:**

```json
{
  "floor_id": 1,
  "name": "Room 101",
  "code": "EB-101",
  "type": "classroom",
  "description": "Main lecture hall",
  "facilities": [1, 2]
}
```

**Response `201`:**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "floor_id": 1,
    "name": "Room 101",
    "code": "EB-101",
    "type": "classroom",
    "description": "Main lecture hall",
    "facilities": [
      { "id": 1, "name": "WiFi", "icon": "wifi" },
      { "id": 2, "name": "Projector", "icon": "projector" }
    ]
  },
  "error": null
}
```

#### GET /api/admin/rooms/{id}
#### PUT/PATCH /api/admin/rooms/{id}
#### DELETE /api/admin/rooms/{id}

---

### Facilities

#### GET /api/admin/facilities

List all facilities.

**Response `200`:**

```json
{
  "success": true,
  "data": [
    { "id": 1, "name": "WiFi", "icon": "wifi" }
  ],
  "error": null
}
```

#### POST /api/admin/facilities

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `name` | string | yes | |
| `icon` | string | yes | Icon identifier string |

**Request body:**

```json
{ "name": "Air Conditioning", "icon": "ac" }
```

#### GET /api/admin/facilities/{id}
#### PUT/PATCH /api/admin/facilities/{id}
#### DELETE /api/admin/facilities/{id}

---

### Origins

#### GET /api/admin/origins

List all navigation origins.

#### POST /api/admin/origins

| Field | Type | Required |
|-------|------|----------|
| `name` | string | yes |
| `code` | string | yes |
| `description` | string | no |

**Request body:**

```json
{
  "name": "North Gate",
  "code": "NG",
  "description": "Secondary entrance from north road"
}
```

#### GET /api/admin/origins/{id}
#### PUT/PATCH /api/admin/origins/{id}
#### DELETE /api/admin/origins/{id}

---

### Routes

#### GET /api/admin/routes

List all routes.

#### POST /api/admin/routes

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `origin_id` | integer | yes | |
| `destination_room_id` | integer | yes | |
| `name` | string | yes | |
| `description` | string | no | |
| `steps` | object[] | yes | Ordered list of navigation steps |

**Step object:**

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `order` | integer | yes | 1-based, must be unique within route |
| `instruction` | string | yes | Human-readable direction text |
| `direction` | string | yes | `straight`, `left`, `right`, `up`, `down` |
| `landmark` | string | no | Nearby visual reference |

**Request body:**

```json
{
  "origin_id": 1,
  "destination_room_id": 4,
  "name": "Main Gate to EB-101",
  "description": null,
  "steps": [
    {
      "order": 1,
      "instruction": "Walk straight past the guard post.",
      "direction": "straight",
      "landmark": "Guard post"
    },
    {
      "order": 2,
      "instruction": "Turn left at the fountain.",
      "direction": "left",
      "landmark": "Fountain"
    },
    {
      "order": 3,
      "instruction": "Enter the Engineering Building and take the stairs up.",
      "direction": "up",
      "landmark": "Engineering Building entrance"
    }
  ]
}
```

**Response `201`:**

```json
{
  "success": true,
  "data": {
    "id": 5,
    "origin_id": 1,
    "destination_room_id": 4,
    "name": "Main Gate to EB-101",
    "description": null,
    "steps": [ ... ]
  },
  "error": null
}
```

#### GET /api/admin/routes/{id}
#### PUT/PATCH /api/admin/routes/{id}
#### DELETE /api/admin/routes/{id}

---

### POST /api/admin/publish

Publishes the current campus data and increments the version counter. Students will see the new version on their next `GET /api/sync/version` check and re-fetch bootstrap.

**Request body:**

```json
{
  "note": "Added Science Building floors and rooms"
}
```

| Field | Type | Required |
|-------|------|----------|
| `note` | string | no |

**Response `200`:**

```json
{
  "success": true,
  "data": {
    "version": 15,
    "note": "Added Science Building floors and rooms",
    "published_at": "2026-03-10T10:30:00Z"
  },
  "error": null
}
```

---

## Data Models

### Relationships

```
Building
  └── Floor (building_id)
        └── Room (floor_id)
              └── Facility (many-to-many via room_facilities)

Origin
  └── CampusRoute (origin_id, destination_room_id → Room)
        └── RouteStep (route_id, order, instruction, direction, landmark)
```

### Room types

| Value | Description |
|-------|-------------|
| `classroom` | Lecture rooms, tutorial rooms |
| `office` | Faculty and admin offices |
| `lab` | Laboratories |
| `restroom` | Comfort rooms |
| `other` | Everything else |

### Step directions

| Value | Description |
|-------|-------------|
| `straight` | Continue forward |
| `left` | Turn left |
| `right` | Turn right |
| `up` | Go upstairs / up ramp |
| `down` | Go downstairs / down ramp |

---

## Sync Strategy

The app follows a version-check-then-fetch pattern to minimize bandwidth.

```
App launch
  │
  ├── GET /api/sync/version
  │     └── compare to locally stored version
  │
  ├── version matches → use local data, skip network fetch
  │
  └── version differs (or no local data)
        └── GET /api/sync/bootstrap → replace local cache entirely
```

**Recommendations for the Android client:**

- Store `version` and the bootstrap payload in a local database (e.g. Room DB).
- Call `GET /api/sync/version` on every app foreground resume.
- Only call `GET /api/sync/bootstrap` when the version number has changed.
- Route lookups (`GET /api/routes/{roomId}?origin_id={id}`) are auth-required but can fall back to cached bootstrap data when offline.
- Admin users have no offline write capability — all admin write endpoints require a live connection.
