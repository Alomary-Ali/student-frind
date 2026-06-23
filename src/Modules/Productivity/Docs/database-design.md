# Productivity Module — Database Design

**Module:** Productivity  
**Last Updated:** 2026-06-20  
**Convention:** ADR-005 (UUID PKs, `productivity_` prefix)

---

## Entity Relationship Diagram

```mermaid
erDiagram
    users ||--o{ productivity_goals : "has"
    users ||--o{ productivity_tasks : "has"
    users ||--o{ productivity_reminders : "has"
    users ||--o{ productivity_calendar_events : "has"
    users ||--o{ productivity_snapshots : "has"
    users ||--o{ productivity_audit_logs : "performs"
    productivity_goals ||--o{ productivity_tasks : "linked to"
    productivity_tasks ||--o{ productivity_reminders : "linked to"
    productivity_tasks ||--o{ productivity_calendar_events : "linked to"

    productivity_goals {
        uuid id PK
        uuid user_id FK
        string title
        text description
        date target_date
        string priority
        decimal progress
        string status
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    productivity_tasks {
        uuid id PK
        uuid user_id FK
        string title
        text description
        datetime due_date
        string priority
        string status
        uuid linked_goal_id FK
        datetime completed_at
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    productivity_reminders {
        uuid id PK
        uuid user_id FK
        string message
        datetime trigger_at
        string type
        uuid linked_task_id FK
        string status
        datetime triggered_at
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    productivity_calendar_events {
        uuid id PK
        uuid user_id FK
        string title
        text description
        datetime starts_at
        datetime ends_at
        boolean is_all_day
        uuid linked_task_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    productivity_snapshots {
        uuid id PK
        uuid user_id FK
        integer total_goals
        integer completed_goals
        integer total_tasks
        integer completed_tasks
        integer overdue_tasks
        decimal completion_rate
        date snapshot_date
        timestamp created_at
        timestamp updated_at
    }

    productivity_audit_logs {
        uuid id PK
        uuid actor_user_id FK
        string action
        string entity_type
        uuid entity_id
        json new_values
        json old_values
        string ip_address
        string user_agent
        timestamp created_at
        timestamp updated_at
    }
```

---

## Tables

| Table | Purpose |
|-------|---------|
| `productivity_goals` | Student goals and objectives |
| `productivity_tasks` | Individual tasks linked to goals or standalone |
| `productivity_reminders` | Notifications for tasks and events |
| `productivity_calendar_events` | Calendar events and appointments |
| `productivity_snapshots` | Historical productivity metrics |
| `productivity_audit_logs` | Critical operation audit trail |

---

## Indexes

- All foreign keys indexed
- Indexes on `user_id` for all tables
- Indexes on `status` for goals, tasks, reminders
- Indexes on `target_date`, `due_date`, `trigger_at`, `starts_at`, `ends_at` for date queries
- Indexes on `priority` for filtering
- Indexes on `snapshot_date` for snapshots
- Indexes on `action`, `entity_type`, `entity_id` for audit logs

---

## Referential Integrity

- `productivity_goals.user_id` → `users.id` (cascade delete)
- `productivity_tasks.user_id` → `users.id` (cascade delete)
- `productivity_tasks.linked_goal_id` → `productivity_goals.id` (restrict)
- `productivity_reminders.user_id` → `users.id` (cascade delete)
- `productivity_reminders.linked_task_id` → `productivity_tasks.id` (restrict)
- `productivity_calendar_events.user_id` → `users.id` (cascade delete)
- `productivity_calendar_events.linked_task_id` → `productivity_tasks.id` (restrict)
- `productivity_snapshots.user_id` → `users.id` (cascade delete)
- `productivity_audit_logs.actor_user_id` → `users.id` (restrict)

Cross-module FK only to Shared `users` table (per ADR-003).
