# Productivity Module — API Documentation

**Base URL:** `/api/v1/productivity`  
**Format:** JSON  
**Auth:** Laravel Sanctum (Bearer token) for protected endpoints

---

## Response Format

### Success
```json
{
  "success": true,
  "message": "",
  "data": {}
}
```

### Error
```json
{
  "success": false,
  "code": "NOT_FOUND",
  "message": "Goal not found.",
  "errors": {}
}
```

---

## Endpoints

### Goals

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/goals` | No | Create a new goal |
| GET | `/goals/{id}` | No | Get goal by ID |
| GET | `/users/{userId}/goals` | No | Get all goals for a user |
| PATCH | `/goals/{id}/progress` | No | Update goal progress |

**Valid `goal_type` values:** `daily`, `semester`, `long_term`
**Valid `priority` values:** `low`, `medium`, `high`, `urgent`

#### POST `/goals`
```json
{
  "user_id": "uuid",
  "title": "Complete semester with 3.5 GPA",
  "description": "Maintain high academic performance",
  "target_date": "2026-12-31",
  "priority": "high",
  "goal_type": "semester"
}
```

#### PATCH `/goals/{id}/progress`
```json
{
  "progress": 75.5
}
```

### Tasks

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/tasks` | No | Create a new task |
| GET | `/tasks/{id}` | No | Get task by ID |
| GET | `/users/{userId}/tasks` | No | Get all tasks for a user |
| POST | `/tasks/{id}/complete` | No | Mark task as completed |

#### POST `/tasks`
```json
{
  "user_id": "uuid",
  "title": "Complete assignment",
  "description": "Finish the math homework",
  "due_date": "2026-06-25 23:59:59",
  "priority": "medium",
  "linked_goal_id": "uuid (optional)"
}
```

### Reminders

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/reminders` | No | Create a new reminder |
| GET | `/users/{userId}/reminders` | No | Get all reminders for a user |

#### POST `/reminders`
```json
{
  "user_id": "uuid",
  "message": "Study for exam tomorrow",
  "trigger_at": "2026-06-25 09:00:00",
  "type": "in_app",
  "linked_task_id": "uuid (optional)"
}
```

**Valid task statuses:** `pending`, `in_progress`, `completed`, `postponed`, `cancelled`
**Valid types:** `email`, `push`, `in_app`

### Calendar Events

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/calendar-events` | No | Create a new calendar event |
| GET | `/users/{userId}/calendar-events` | No | Get all calendar events for a user |

#### POST `/calendar-events`
```json
{
  "user_id": "uuid",
  "title": "Study Group",
  "description": "Math study group meeting",
  "starts_at": "2026-06-25 14:00:00",
  "ends_at": "2026-06-25 16:00:00",
  "is_all_day": false,
  "linked_task_id": "uuid (optional)"
}
```

### Dashboard

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/users/{userId}/dashboard` | No | Get productivity dashboard |
| POST | `/users/{userId}/snapshots` | No | Generate productivity snapshot |

#### POST `/users/{userId}/snapshots`
```json
{
  "snapshot_date": "2026-06-25"
}
```

### Exams (Old-Style)

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/exams` | No | Create a new exam |
| GET | `/exams/{id}` | No | Get exam details |
| GET | `/users/{userId}/exams` | No | Get all exams for a user |
| POST | `/exams/{id}/status` | No | Update exam status |

**Valid `exam_type` values:** `midterm`, `final`, `quiz`, `practical`, `oral`
**Valid `readiness_status` values:** `not_ready`, `needs_review`, `partially_ready`, `fully_ready`

#### POST `/exams`
```json
{
  "user_id": "uuid",
  "course_id": "uuid",
  "title": "Midterm Exam",
  "exam_type": "midterm",
  "exam_date": "2026-07-15 10:00:00",
  "location": "Hall A",
  "readiness_status": "needs_review"
}
```

---

## Status Codes

| Code | Usage |
|------|-------|
| 200 | Successful GET |
| 201 | Successful POST (create) |
| 404 | Resource not found |
| 409 | Business rule conflict |
| 422 | Validation error |
| 401 | Unauthenticated (protected routes) |
