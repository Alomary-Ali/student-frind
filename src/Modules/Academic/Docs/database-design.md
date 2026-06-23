# Academic Module — Database Design

**Module:** Academic  
**Last Updated:** 2026-06-16  
**Convention:** ADR-005 (UUID PKs, `academic_` prefix)

---

## Entity Relationship Diagram

```mermaid
erDiagram
    users ||--o| academic_students : "has profile"
    academic_students ||--o{ academic_plans : "assigned"
    academic_students ||--o{ academic_enrollments : "enrolls"
    academic_students ||--o| academic_graduation_paths : "tracks"
    academic_curricula ||--o{ academic_plans : "defines"
    academic_curricula ||--o{ academic_curriculum_courses : "contains"
    academic_courses ||--o{ academic_curriculum_courses : "listed in"
    academic_courses ||--o{ academic_enrollments : "enrolled in"
    academic_semesters ||--o{ academic_enrollments : "during"
    academic_enrollments ||--o| academic_records : "graded"

    academic_students {
        uuid id PK
        uuid user_id FK
        string student_number UK
        string academic_status
        string academic_standing
        decimal cumulative_gpa
        uuid institution_id
    }

    academic_courses {
        uuid id PK
        string code UK
        string title
        int credit_hours
        boolean is_active
    }

    academic_semesters {
        uuid id PK
        string code UK
        date start_date
        date end_date
    }

    academic_curricula {
        uuid id PK
        string code UK
        int total_credits_required
    }

    academic_plans {
        uuid id PK
        uuid student_id FK
        uuid curriculum_id FK
        string status
    }

    academic_enrollments {
        uuid id PK
        uuid student_id FK
        uuid course_id FK
        uuid semester_id FK
        string status
    }

    academic_records {
        uuid id PK
        uuid enrollment_id FK UK
        string grade_letter
        decimal grade_points
    }

    academic_graduation_paths {
        uuid id PK
        uuid student_id FK UK
        int credits_earned
        int credits_required
        decimal completion_percentage
    }
```

---

## Tables

| Table | Purpose |
|-------|---------|
| `academic_students` | Student academic profile (links to `users`) |
| `academic_courses` | Course catalog |
| `academic_semesters` | Academic terms |
| `academic_curricula` | Degree/program structures |
| `academic_curriculum_courses` | Curriculum-to-course mapping |
| `academic_plans` | Student academic plan assignments |
| `academic_enrollments` | Course registrations per semester |
| `academic_records` | Grade records (soft-delete not used — audit via `academic_audit_logs`) |
| `academic_graduation_paths` | Graduation progress tracking |
| `academic_audit_logs` | Critical operation audit trail |

---

## Multi-University Readiness

All core tables include nullable `institution_id` (UUID) for future tenant isolation. Multi-tenancy is **not** implemented; column is reserved for Phase 9 (Administration module).

---

## Indexes

- All foreign keys indexed
- Unique constraints: `student_number`, `course.code`, `semester.code`, `curriculum.code`
- Composite unique: `(student_id, course_id, semester_id)` on enrollments
- Composite index: `(student_id, status)` on plans

---

## Referential Integrity

- `academic_students.user_id` → `users.id` (cascade delete)
- Cross-module FK only to Shared `users` table (per ADR-003)
- Intra-module FKs use `restrictOnDelete` where historical integrity matters
