# ADR-003 — Module Boundary Enforcement Rules

| Field       | Value            |
|-------------|------------------|
| **Status**  | ✅ Accepted       |
| **Date**    | 2026-06-15        |
| **Authors** | Chief Architect  |

---

## Context

In a Modular Monolith, the greatest risk is **module coupling creep** — where modules gradually start depending on each other's internals. This turns the codebase into a "distributed monolith" where everything is coupled but nothing is isolated.

This ADR defines strict, enforceable rules for how modules communicate.

---

## Module Dependency Map

```
                    ┌──────────┐
                    │  Shared  │  (foundation for all modules)
                    └────┬─────┘
                         │ (User identity, Auth, Notifications, Files)
          ┌──────────────┼──────────────┐
          ▼              ▼              ▼
     Academic       Productivity    Skills ──────►  CareerProfile
          │              │              │
          └──────┬───────┘              │
                 ▼                      ▼
             Guidance ◄──────────── Opportunities
                 │
          ┌──────┴──────┐
          ▼             ▼
      Community      Analytics
          │
          ▼
    Administration
```

**Legend:**
- Arrow direction = "depends on" (reads data from / listens to events from)
- All modules depend on `Shared` for User identity
- `Guidance` depends on multiple modules for cross-domain recommendations
- `Analytics` reads from all modules (read-only, via events)

---

## Communication Rules

### ✅ ALLOWED — Contracts (Synchronous)

A module may define interfaces in its `Domain/Contracts/` folder that other modules can implement or call.

```
// Academic module exposes:
Modules\Academic\Domain\Contracts\AcademicPlanReaderInterface

// Guidance module calls it (injected, not coupled to implementation):
public function __construct(
    private readonly AcademicPlanReaderInterface $academicPlans,
) {}
```

**Rules:**
- Interface must be in the **publishing module's** `Domain/Contracts/`
- Only **read** operations are exposed as contracts (write operations via events)
- Contract interface must return **DTOs**, never internal domain entities

---

### ✅ ALLOWED — Domain Events (Asynchronous)

Modules communicate state changes by publishing Domain Events. Other modules listen.

```
// Academic module publishes:
new StudentEnrolled(studentId: '...', courseId: '...', enrolledAt: now())

// Skills module listens (in its Infrastructure/):
class AddEnrollmentToSkillProfileOnStudentEnrolled
{
    public function handle(StudentEnrolled $event): void { ... }
}
```

**Rules:**
- Event classes live in the **publishing module's** `Domain/Events/`
- Listeners live in the **consuming module's** `Infrastructure/` or `Application/`
- Events are the **only** way to trigger side effects across modules

---

### ✅ ALLOWED — Shared Module Usage

All modules may use Shared module services:
- `UserRepositoryInterface` — look up user identity
- `NotificationDispatcher` — send notifications
- `FileStorageInterface` — upload/retrieve files
- `AuditLogger` — record domain events for compliance

---

### ❌ FORBIDDEN — Direct Model/Entity Import

```php
// FORBIDDEN — Academic importing Productivity's Entity
use Modules\Productivity\Domain\Entities\Task;

// FORBIDDEN — Controller in Academic querying Productivity's DB
DB::table('productivity_tasks')->where('student_id', $id)->get();
```

### ❌ FORBIDDEN — Cross-Module Eloquent Access

```php
// FORBIDDEN — Skill module using Eloquent model from CareerProfile
use Modules\CareerProfile\Infrastructure\Persistence\EloquentCareerProfile;
```

### ❌ FORBIDDEN — Controller-to-Controller Calls

Modules must not call each other's Controllers or HTTP endpoints internally.

### ❌ FORBIDDEN — Shared Database Joins Across Module Boundaries

```php
// FORBIDDEN — joining tables from two different modules
DB::table('academic_enrollments')
    ->join('productivity_tasks', ...)
    ->get();
```

If cross-module data is needed in a single query (e.g., Analytics), use **read-model projections** maintained by Analytics via event listeners.

---

## Public API Surface per Module

Each module exposes a defined public surface. Everything else is **private** to the module.

| Module          | Public Surface                                                  |
|-----------------|------------------------------------------------------------------|
| Shared          | `UserRepositoryInterface`, `NotificationDispatcher`, `AuditLogger`, `FileStorageInterface` |
| Academic        | `AcademicPlanReaderInterface`, Events: `StudentEnrolled`, `GradeRecorded`, `CourseCompleted` |
| Productivity    | Events: `TaskCompleted`, `GoalAchieved`                         |
| Guidance        | Events: `EarlyAlertTriggered`, `RecommendationGenerated`        |
| Skills          | `SkillProfileReaderInterface`, Events: `SkillAdded`, `CertificateEarned` |
| CareerProfile   | `CareerProfileReaderInterface`, Events: `PortfolioPublished`    |
| Opportunities   | Events: `OpportunityApplied`, `ApplicationStatusChanged`        |
| Community       | Events: `GroupJoined`, `MentorshipStarted`                      |
| Analytics       | (Read-only module — no public surface needed)                   |
| Administration  | `TenantReaderInterface`, Events: `UserRoleAssigned`             |

---

## Adding a New Module

1. Create `src/Modules/{NewModule}/` with full layer structure
2. Create `{NewModule}ServiceProvider.php`
3. Register it in `app/Providers/ModuleServiceProvider.php`
4. Add PSR-4 namespace to `composer.json`
5. Define public surface in module `README.md`
6. Update this ADR's dependency map

---

## Related

- ADR-001: Project Architecture
- ADR-002: DDD Rules
- ADR-004: Event-Driven Design
