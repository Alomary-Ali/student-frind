# ADR-005 — Database Design Conventions

| Field       | Value            |
|-------------|------------------|
| **Status**  | ✅ Accepted       |
| **Date**    | 2026-06-15        |
| **Authors** | Chief Architect  |

---

## Context

Consistent database design is critical for long-term maintainability, performance, and data integrity. This ADR establishes binding conventions for all database tables across all SSP modules.

---

## 1. Primary Keys — UUID v4

**Rule:** All tables use UUID v4 string primary keys.

```php
// In every migration:
$table->uuid('id')->primary();
```

**Rationale:**
- No sequential ID exposure (security — prevents enumeration attacks)
- Globally unique across tables and potential future service decomposition
- Generated at the application layer — no DB round-trip needed to get the ID
- Works correctly in distributed/multi-tenant environments

**Trade-offs:**
- Larger index size vs. integer (16 bytes vs. 4-8 bytes)
- Slightly slower sequential scans — mitigated by proper indexing

---

## 2. Required Columns on Every Table

Every table in SSP **must** contain these columns:

```php
$table->uuid('id')->primary();
$table->timestamps();          // created_at, updated_at
```

**No exceptions.** Tables without these columns will be rejected in code review.

---

## 3. Soft Delete Policy

Soft delete (`deleted_at`) is **NOT** the default.

**Use soft delete ONLY when:**
- The record must be retained for audit/compliance purposes after "deletion"
- Historical references from other tables must remain valid
- Example: `User` accounts (deactivated, not deleted), `Grade` records

**Do NOT use soft delete for:**
- Lookup data / reference tables (course catalog items)
- Event logs (append-only, never deleted)
- Session data

```php
// Only when justified:
$table->softDeletes();
```

---

## 4. Table Naming

- **snake_case**, **plural** noun
- **Module-prefixed** to avoid naming collisions across modules

| Module          | Table Prefix         | Example Tables                                     |
|-----------------|----------------------|----------------------------------------------------|
| Academic        | `academic_`          | `academic_plans`, `academic_enrollments`, `academic_grades` |
| Productivity    | `productivity_`      | `productivity_tasks`, `productivity_goals`, `productivity_habits` |
| Guidance        | `guidance_`          | `guidance_sessions`, `guidance_alerts`, `guidance_recommendations` |
| Skills          | `skills_`            | `skills_profiles`, `skills_competencies`, `skills_certificates` |
| CareerProfile   | `career_`            | `career_profiles`, `career_portfolios`, `career_portfolio_items` |
| Opportunities   | `opportunities_`     | `opportunities`, `opportunity_applications`        |
| Community       | `community_`         | `community_groups`, `community_posts`, `community_events` |
| Analytics       | `analytics_`         | `analytics_snapshots`, `analytics_reports`         |
| Administration  | `admin_`             | `admin_universities`, `admin_roles`, `admin_settings` |
| Shared          | (no prefix)          | `users`, `notifications`, `files`, `audit_logs`    |

---

## 5. Foreign Keys

- All foreign key columns use the naming convention: `{related_table_singular}_id`
- Example: `student_id`, `course_id`, `academic_plan_id`
- Foreign key constraints **must be declared** in migrations

```php
$table->uuid('student_id');
$table->foreign('student_id')->references('id')->on('users')->cascadeOnDelete();
```

**Cross-module foreign keys:**
- Cross-module FK references (e.g., `academic_enrollments.student_id → users.id`) are allowed only to the `Shared` module's tables
- Modules must NOT have FK constraints pointing to other domain modules' tables
- Cross-module relationships are maintained via application-level consistency (events), not DB constraints

---

## 6. Normalization Rules

- **Minimum 3NF (Third Normal Form)** for all tables
- No repeating groups in columns (use related tables)
- No calculated/derived data stored (compute at query time or via read models)
- No JSON columns for structured data that needs querying
- JSON columns allowed ONLY for: configuration blobs, metadata, external API response caching

---

## 7. Nullable Fields Policy

**Avoid nullable fields.** A nullable column often signals a design problem.

**Instead:**
- Use a **separate table** for optional data (e.g., `career_profile_social_links` table instead of nullable columns)
- Use **sensible defaults** for non-nullable fields
- Use a **separate status/type column** instead of null to represent absence

**When nullable IS acceptable:**
- `deleted_at` (soft delete timestamp)
- External IDs from third-party systems (e.g., `linkedin_profile_id`)
- Optional free-text fields with no business rules (e.g., `bio`)

---

## 8. Index Strategy

**Always index:**
- All foreign key columns
- All columns used in `WHERE` clauses for filtering
- All columns used in `ORDER BY` for pagination
- Unique constraints on natural unique keys (e.g., `email`)

```php
$table->index('student_id');
$table->index('academic_term');
$table->index(['student_id', 'academic_term']);  // composite for common filter
$table->unique('email');
```

**Avoid over-indexing:**
- Don't index columns rarely used in queries
- Don't index boolean flags (low cardinality — full scans are often faster)

---

## 9. Migration File Naming

```
YYYY_MM_DD_HHMMSS_create_{module_prefix}_{table_name}_table.php
YYYY_MM_DD_HHMMSS_add_{column}_to_{table_name}_table.php
```

Migrations live in each module's `Infrastructure/Persistence/Migrations/` directory and are loaded by the module's ServiceProvider.

---

## 10. Eloquent Model Placement

- Eloquent models live **exclusively** in `Infrastructure/Persistence/`
- Model class names: `Eloquent{EntityName}` — e.g., `EloquentStudent`, `EloquentAcademicPlan`
- Eloquent models must **not** be imported or referenced in Domain or Application layers

---

## Related

- ADR-001: Project Architecture
- ADR-002: DDD Rules (Repository Pattern)
