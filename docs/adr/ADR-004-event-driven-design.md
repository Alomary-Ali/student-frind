# ADR-004 — Event-Driven Communication Between Modules

| Field       | Value            |
|-------------|------------------|
| **Status**  | ✅ Accepted       |
| **Date**    | 2026-06-15        |
| **Authors** | Chief Architect  |

---

## Context

Modules must communicate without direct coupling. Domain Events are the primary mechanism for one module to inform others that something meaningful happened in the domain — without knowing who is listening.

---

## Decision

All cross-module side effects are triggered via **Domain Events**. No module calls another module's internal services directly for reactive workflows.

---

## Event Naming Convention

**Pattern:** `[Entity][PastTenseVerb]`

| ✅ Good                    | ❌ Bad                   |
|---------------------------|--------------------------|
| `StudentEnrolled`         | `OnEnroll`               |
| `GradeRecorded`           | `GradeEvent`             |
| `CourseCompleted`         | `UpdateCourse`           |
| `PortfolioPublished`      | `PortfolioAction`        |
| `EarlyAlertTriggered`     | `Alert`                  |
| `OpportunityApplied`      | `ApplicationSubmitted`   |

---

## Event Payload Standard

### Rules
1. **Flat structure** — no nested objects or domain entities
2. **All IDs as `string` (UUID)** — never pass Entity objects across module boundaries
3. **Include `occurredAt: \DateTimeImmutable`** — for temporal reasoning and ordering
4. **`final readonly` classes** — events are immutable facts
5. **No behavior** — events are pure data (no methods except constructor)

### Template

```php
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

final readonly class StudentEnrolled
{
    public function __construct(
        public string $studentId,
        public string $courseId,
        public string $academicPlanId,
        public string $academicTerm,
        public \DateTimeImmutable $occurredAt,
    ) {}
}
```

---

## Synchronous vs. Asynchronous Rules

### Synchronous (inline dispatch)
Use when the side effect must complete **before** returning a response.

**Examples:**
- Sending a confirmation notification immediately after enrollment
- Updating a counter that the same request reads back

```php
// Synchronous listener — no ShouldQueue
final class SendEnrollmentConfirmationNotification
{
    public function handle(StudentEnrolled $event): void
    {
        // Send email synchronously
    }
}
```

### Asynchronous (queued dispatch)
Use when the side effect is **independent** of the current request.

**Examples:**
- Rebuilding Analytics read models after a grade is recorded
- Updating Skill Profile when a course is completed
- Sending batch notifications

```php
// Asynchronous listener — implements ShouldQueue
final class UpdateSkillProfileOnCourseCompleted implements ShouldQueue
{
    public string $queue = 'module-events';

    public function handle(CourseCompleted $event): void
    {
        // Update skill profile asynchronously
    }
}
```

**Default rule:** When in doubt, use **asynchronous**. Only use synchronous if the business requirement explicitly requires immediate consistency.

---

## Event Registration

Events and listeners are registered in each module's ServiceProvider:

```php
// In AcademicServiceProvider:
public function boot(): void
{
    Event::listen(
        StudentEnrolled::class,
        SendEnrollmentConfirmationNotification::class,
    );
}

// In SkillsServiceProvider:
public function boot(): void
{
    Event::listen(
        \Modules\Academic\Domain\Events\CourseCompleted::class,
        UpdateSkillProfileOnCourseCompleted::class,
    );
}
```

---

## Event Versioning Strategy

When an event payload must change (new field required):

1. **Backward-compatible addition (new optional field):** Add with a default value — no version bump needed.
2. **Breaking change (rename/remove field):** Create `StudentEnrolledV2` — keep the original event dispatching until all listeners are migrated.
3. **Remove old event:** Only after all listeners have been updated to `V2` and old event has been removed from all dispatch calls.

---

## Example Events per Module

### Academic Module
```
StudentEnrolled         — student registered in a course
GradeRecorded           — a grade was assigned to an enrollment
CourseCompleted         — student passed a course with passing grade
GpaCalculated           — student's GPA was recalculated
AcademicPlanCreated     — a new academic plan was created
GraduationProgressUpdated — graduation progress recalculated
```

### Productivity Module
```
TaskCreated             — student created a new task
TaskCompleted           — student marked a task as done
GoalAchieved            — student achieved a defined goal
HabitStreakReached      — student maintained a habit for N days
```

### Skills Module
```
SkillAdded              — student added a skill to their profile
CertificateEarned       — student uploaded/earned a certificate
CompetencyLevelUpdated  — a competency level was raised
```

### CareerProfile Module
```
CareerProfileCreated    — student created their career profile
PortfolioPublished      — student made their portfolio public
PortfolioItemAdded      — student added a portfolio item
```

### Opportunities Module
```
OpportunityPosted       — employer posted a new opportunity
OpportunityApplied      — student submitted an application
ApplicationStatusChanged — employer changed application status
```

### Guidance Module
```
EarlyAlertTriggered     — system detected an at-risk indicator
AdvisingSessionScheduled — session booked between student and advisor
RecommendationGenerated — AI/rule engine generated a recommendation
```

### Community Module
```
GroupCreated            — new community group was created
GroupJoined             — student joined a group
MentorshipStarted       — student-mentor relationship established
EventRsvpConfirmed      — student RSVPd to a community event
```

### Shared Module
```
UserRegistered          — new user created an account
UserEmailVerified       — user verified their email
UserRoleAssigned        — admin assigned a role to a user
NotificationSent        — a notification was dispatched
```

---

## Related

- ADR-001: Project Architecture
- ADR-002: DDD Rules (Domain Events section)
- ADR-003: Module Boundaries
