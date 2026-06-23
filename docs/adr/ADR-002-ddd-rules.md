# ADR-002 — Domain-Driven Design Rules and Boundaries

| Field       | Value                        |
|-------------|------------------------------|
| **Status**  | ✅ Accepted                  |
| **Date**    | 2026-06-15                   |
| **Authors** | Chief Architect              |

---

## Context

DDD is the primary design philosophy for SSP. This ADR defines the rules that govern how DDD concepts are applied consistently across all 10 modules.

---

## Decision

Apply DDD tactical patterns with the following rules enforced across all modules.

---

## 1. Aggregate Boundaries

Each module has one primary **Aggregate Root** that controls consistency within the module.

| Module          | Aggregate Root       | Key Children                          |
|-----------------|----------------------|---------------------------------------|
| Academic        | `Student` (by ref)   | `AcademicPlan`, `CourseEnrollment`, `Grade` |
| Productivity    | `Schedule`           | `Task`, `Goal`, `Habit`               |
| Guidance        | `AdvisingSession`    | `EarlyAlert`, `Recommendation`        |
| Skills          | `SkillProfile`       | `Competency`, `Certificate`           |
| CareerProfile   | `CareerProfile`      | `Portfolio`, `PortfolioItem`          |
| Opportunities   | `Opportunity`        | `OpportunityApplication`             |
| Community       | `CommunityGroup`     | `Forum`, `Post`, `Event`             |
| Analytics       | `Report`             | `KpiSnapshot`, `CohortAnalysis`      |
| Administration  | `University`         | `SystemSetting`, `UserRole`          |
| Shared          | `User`               | (identity only)                       |

### Aggregate Rules
- An aggregate is the **only entry point** for modifying its children
- External modules reference aggregates **by ID only** — never import the class
- Aggregates enforce all **business invariants** (never allow invalid state)
- Keep aggregates **small** — if an aggregate exceeds 5 entities, consider splitting

---

## 2. Entity vs. Value Object Decision Rules

### Use an **Entity** when:
- The object has a distinct lifecycle (created, updated, deleted)
- It has a unique identity (UUID)
- Two instances with the same data are still different objects
- Example: `CourseEnrollment`, `AdvisingSession`, `OpportunityApplication`

### Use a **Value Object** when:
- The object is defined entirely by its attributes (no identity)
- It is **immutable** — once created, never modified
- Two instances with the same data are equal
- Example: `GradePoint`, `EmailAddress`, `AcademicTerm`, `Grade`

### Value Object Implementation Rules
```php
final class GradePoint
{
    // Private constructor — use factory method
    private function __construct(private readonly float $value) {}

    // Named factory with invariant enforcement
    public static function of(float $value): self
    {
        if ($value < 0.0 || $value > 4.0) {
            throw InvalidGradePointException::outOfRange($value);
        }
        return new self($value);
    }

    // Equality by value
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function value(): float { return $this->value; }
}
```

---

## 3. Domain Services

Use a **Domain Service** when:
- An operation involves multiple aggregates that don't own each other
- The logic does not naturally belong to any single entity
- Example: `CalculateGraduationProgress` (needs AcademicPlan + all Enrollments + GradeRecords)

Domain Services **must not**:
- Use Laravel Facades
- Access the database directly
- Depend on HTTP or external I/O

---

## 4. Repository Pattern

- Every Aggregate Root has exactly one **Repository Interface** in `Domain/Contracts/`
- The interface is pure PHP — no Eloquent types
- The implementation lives in `Infrastructure/Repositories/`
- Repository methods return **Domain Entities**, not Eloquent models

```php
// Domain/Contracts/AcademicPlanRepositoryInterface.php
interface AcademicPlanRepositoryInterface
{
    public function findById(AcademicPlanId $id): ?AcademicPlan;
    public function findByStudentId(StudentId $studentId): ?AcademicPlan;
    public function save(AcademicPlan $plan): void;
}
```

---

## 5. Domain Events

Domain Events are **facts** — something that happened in the domain.

### Event Naming: `[Entity][PastTense]`
- ✅ `StudentEnrolled`, `CourseCompleted`, `GradeRecorded`, `PortfolioPublished`
- ❌ `OnEnroll`, `CourseEvent`, `UpdateGrade`

### Event Properties
- Flat structure (no nested domain objects)
- All IDs as strings (UUIDs)
- Include `occurredAt: \DateTimeImmutable`

### Raising Events
Entities raise events internally; the Application layer dispatches them:
```php
// Inside Entity:
$this->events[] = new StudentEnrolled($this->id->value(), $courseId->value(), now());

// In Application Use Case:
foreach ($student->releaseEvents() as $event) {
    $this->dispatcher->dispatch($event);
}
```

---

## 6. Invariant Enforcement

Business rules that **must always be true** are enforced inside the Domain layer.

```php
// In AcademicPlan entity:
public function addCourse(Course $course): void
{
    if ($this->isLocked()) {
        throw AcademicPlanIsLockedException::forPlan($this->id);
    }
    if ($this->courses->contains($course->id())) {
        throw CourseAlreadyInPlanException::forCourse($course->id());
    }
    $this->courses->add($course);
    $this->events[] = new CourseAddedToPlan($this->id->value(), $course->id()->value());
}
```

Never enforce business invariants in Controllers, Form Requests, or Middleware.

---

## 7. Ubiquitous Language Enforcement

- Every class name, method name, and variable in the Domain layer **must match** the domain glossary (`/.memory/domain-glossary.md`)
- Pull requests that introduce non-glossary terms must be rejected in code review
- When terminology changes, update the glossary AND all code simultaneously
- New terms must be added to the glossary before being used in code

---

## Related

- ADR-001: Project Architecture
- ADR-003: Module Boundaries
- ADR-004: Event-Driven Design
- `.memory/domain-glossary.md`
- `.memory/coding-standards.md`
