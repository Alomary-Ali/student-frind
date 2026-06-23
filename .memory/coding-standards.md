# Coding Standards — Student Success Platform (SSP)

**Last Updated:** 2026-06-18
**Authority:** Technical Lead / Chief Architect
**Enforcement:** Laravel Pint + PHPStan Level 8

---

## 1. Naming Conventions

### General Principles
- Names must be **explicit and self-documenting**
- No abbreviations unless universally understood (e.g., `Id`, `Url`, `Dto`)
- No generic names: `Manager`, `Helper`, `Util`, `Handler`, `Data`, `Info`, `Service` (alone)

### Classes

| Type              | Convention           | Example                            |
|-------------------|----------------------|------------------------------------|
| Entity            | `PascalCase` noun    | `Student`, `AcademicPlan`          |
| Value Object      | `PascalCase` noun    | `GradePoint`, `EmailAddress`       |
| Domain Event      | `PascalCase` past tense | `StudentEnrolled`, `CourseCompleted` |
| Use Case          | `Verb + Noun`        | `EnrollStudentInCourse`, `CalculateGraduationProgress` |
| Command           | `Verb + Noun + Command` | `CreateAcademicPlanCommand`     |
| Query             | `Verb + Noun + Query` | `GetStudentGpaQuery`              |
| DTO               | `Noun + Dto`         | `StudentProfileDto`                |
| Repository Interface | `I + Noun + Repository` | `IStudentRepository`        |
| Repository Implementation | `Noun + Repository` | `EloquentStudentRepository` |
| Controller        | `Noun + Controller`  | `AcademicPlanController`           |
| Form Request      | `Verb + Noun + Request` | `CreateAcademicPlanRequest`     |
| API Resource      | `Noun + Resource`    | `StudentResource`                  |
| Policy            | `Noun + Policy`      | `AcademicPlanPolicy`               |
| Specification     | `Adjective + Noun + Specification` | `ActiveStudentSpecification` |
| Enum              | `PascalCase` noun    | `GradeStatus`, `AcademicStanding`  |
| Event Listener    | `Verb + On + EventName` | `NotifyAdvisorOnStudentAtRisk`  |

### Methods

```php
// GOOD — explicit, verb-first
public function calculateCumulativeGpa(): GradePoint {}
public function enrollInCourse(CourseId $courseId): void {}
public function isEligibleForGraduation(): bool {}

// BAD — vague, no verb
public function gpa(): float {}
public function course(): void {}
public function graduation(): bool {}
```

### Variables & Properties
- `camelCase` for all variables and properties
- Boolean variables prefixed with `is`, `has`, `can`, `should`: `$isEnrolled`, `$hasCompletedRequirements`
- Collections suffixed with plural noun: `$courses`, `$students`

---

## 2. Class Size Limits

| Constraint          | Limit   | Action if Exceeded         |
|---------------------|---------|----------------------------|
| Lines per class     | 300     | Split responsibility       |
| Lines per method    | 30      | Extract private methods    |
| Lines per controller | 100    | Move logic to Use Case     |
| Constructor params  | 5       | Introduce Parameter Object |

---

## 3. Layer Rules

### Domain Layer — MUST
- ✅ Pure PHP classes only
- ✅ Immutable Value Objects (readonly properties or private constructor + factory)
- ✅ Entities with meaningful business methods
- ✅ Domain Events as simple data classes

### Domain Layer — MUST NOT
- ❌ `use Illuminate\*` in any Domain class
- ❌ Eloquent models in Domain
- ❌ Static calls / Facades
- ❌ HTTP / Request dependencies
- ❌ Direct database queries

### Application Layer — MUST
- ✅ One Use Case class = one business operation
- ✅ Inject repository interfaces (never concrete implementations)
- ✅ Return DTOs (not domain entities directly to presentation)

### Application Layer — MUST NOT
- ❌ SQL queries
- ❌ HTTP logic
- ❌ Controller dependencies

### Infrastructure Layer — MUST
- ✅ Implement all Domain Contracts
- ✅ Eloquent models stay here (never leaked to Domain)
- ✅ Map Eloquent → Domain Entity in repository

### Presentation Layer — MUST
- ✅ Validate input via Form Requests
- ✅ Call one Use Case per endpoint
- ✅ Return API Resource

### Presentation Layer — MUST NOT
- ❌ Business logic in controllers
- ❌ Direct Eloquent queries in controllers
- ❌ Domain entities returned directly from controllers

---

## 4. Controller Template

```php
final class CreateAcademicPlanController extends Controller
{
    public function __construct(
        private readonly CreateAcademicPlan $useCase,
    ) {}

    public function __invoke(CreateAcademicPlanRequest $request): JsonResponse
    {
        $dto = CreateAcademicPlanDto::fromRequest($request);
        $result = $this->useCase->execute($dto);

        return AcademicPlanResource::make($result)
            ->response()
            ->setStatusCode(201);
    }
}
```

---

## 5. Value Object Template

```php
final class GradePoint
{
    private function __construct(
        private readonly float $value,
    ) {}

    public static function of(float $value): self
    {
        if ($value < 0.0 || $value > 4.0) {
            throw new InvalidGradePointException($value);
        }

        return new self($value);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
```

---

## 6. Domain Event Template

```php
final class StudentEnrolled
{
    public function __construct(
        public readonly string $studentId,
        public readonly string $courseId,
        public readonly string $academicTerm,
        public readonly \DateTimeImmutable $enrolledAt,
    ) {}
}
```

---

## 7. Repository Contract Template

```php
// Domain/Contracts/StudentRepositoryInterface.php
interface StudentRepositoryInterface
{
    public function findById(StudentId $id): ?Student;
    public function findByEmail(EmailAddress $email): ?Student;
    public function save(Student $student): void;
    public function delete(StudentId $id): void;
}
```

---

## 8. Use Case Template

```php
final class EnrollStudentInCourse
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly CourseRepositoryInterface $courses,
        private readonly EventDispatcherInterface $events,
    ) {}

    public function execute(EnrollStudentInCourseDto $dto): EnrollmentResultDto
    {
        $student = $this->students->findById(StudentId::of($dto->studentId))
            ?? throw StudentNotFoundException::forId($dto->studentId);

        $course = $this->courses->findById(CourseId::of($dto->courseId))
            ?? throw CourseNotFoundException::forId($dto->courseId);

        $enrollment = $student->enrollIn($course, $dto->academicTerm);

        $this->students->save($student);
        $this->events->dispatch(new StudentEnrolled(
            studentId: $student->id()->value(),
            courseId: $course->id()->value(),
            academicTerm: $dto->academicTerm,
            enrolledAt: new \DateTimeImmutable(),
        ));

        return EnrollmentResultDto::fromEnrollment($enrollment);
    }
}
```

---

## 9. Testing Standards

- Every Use Case must have a Unit Test
- Every Controller endpoint must have a Feature Test
- Tests named: `it_[describes_behavior]` (Pest) or `test_[describes_behavior]` (PHPUnit)
- No testing of private methods directly — test behavior through public API
- Use factories for test data — no raw arrays

---

## 10. Git Commit Convention

```
type(scope): short description

Types: feat | fix | refactor | test | docs | chore | style
Scope: module name (academic, productivity, shared, etc.)

Examples:
feat(academic): add GPA calculation use case
fix(shared): correct email validation in User value object
refactor(career-profile): extract portfolio publishing to dedicated use case
test(guidance): add unit tests for recommendation engine
docs(community): update module README with event contracts
```

---

## 11. Forbidden Patterns

| Pattern                       | Reason                              | Alternative                    |
|-------------------------------|-------------------------------------|--------------------------------|
| `DB::table()` in Use Cases    | Bypasses repository abstraction     | Use Repository interface       |
| `User::find()` in Domain      | Eloquent in Domain layer            | Use `IUserRepository`          |
| `static` business methods     | Untestable, hard to mock            | Inject as dependency           |
| `resolve()` / `app()`         | Hidden dependency                   | Constructor injection          |
| God classes > 300 lines       | Single Responsibility violation     | Extract to separate classes    |
| Cross-module direct imports   | Module coupling                     | Use Contracts or Events        |

---

## 12. Design Standards

### Design System Compliance

All UI/UX implementation must comply with the mandatory Design Constitution.

**Authority:** Chief Product Designer / Design System Architect
**Documentation:** `docs/design-system/`

### Color Standards

**Approved Color Palette — ALL from `app.css` Design Tokens:**

| Token | Value | Usage |
|-------|-------|-------|
| `--color-primary` | `#243B7C` | Headings, primary buttons, important text |
| `--color-navy` | `#06214B` | Featured cards, AI sections, hero areas |
| `--color-accent` | `#10B981` | Success, achievements, positive stats |
| `--color-warning` | `#F59E0B` | Alerts, GPA indicator, attention elements |
| `--color-error` | `#EF4444` | Errors, destructive actions ONLY |
| `--color-background` | `#F8FAFC` | Page backgrounds |
| `--color-surface` | `#FFFFFF` | Card backgrounds |
| `--color-border` | `#E2E8F0` | Borders, dividers |
| `--color-text-primary` | `#1E293B` | Primary text |
| `--color-text-secondary` | `#64748B` | Secondary text |
| `--color-text-muted` | `#94A3B8` | Muted/placeholder text |

**Visual Hierarchy Rule:**
- 75% Neutral (background, surface, borders, text)
- 15% Primary (`#243B7C`)
- 5% Accent (`#10B981`)
- 3% Warning (`#F59E0B`)
- 2% Navy (`#06214B`) — featured elements only

**Allowed Usage:**
```css
/* ✅ CSS variables */
var(--color-primary), var(--color-accent), var(--color-navy)

/* ✅ Tailwind arbitrary with APPROVED values only */
class="bg-[#243B7C] text-[#06214B] border-[#E2E8F0]"
class="bg-[#243B7C]/10 text-[#10B981]/80"

/* ✅ Opacity modifiers on approved colors */
bg-[#243B7C]/5, bg-[#10B981]/10, bg-[#F59E0B]/15
```

**Forbidden:**
```css
/* ❌ Tailwind color classes (not in approved palette) */
class="text-blue-500 bg-green-100 border-red-300"

/* ❌ Any unapproved hex values */
style="color: #FF6B6B" or class="bg-purple-400"

/* ❌ Inline styles for colors */
style="color: #FF0000; background: blue"

/* ❌ Page-specific CSS that overrides app.css tokens */
<style>.my-card { background: #112233; }</style>
```

### Design Token Standards

**Mandatory:** All UI must consume design tokens. Hardcoded values are forbidden.

**Token Categories:**
- Color Tokens
- Typography Tokens
- Spacing Tokens
- Radius Tokens
- Shadow Tokens

**CSS Variables:**
```css
/* Use design tokens, not hardcoded values */
.button {
  background-color: var(--color-primary);
  padding: var(--spacing-2) var(--spacing-4);
  border-radius: var(--radius-md);
}
```

**Tailwind Classes:**
```html
<!-- Use Tailwind utility classes, not inline styles -->
<button className="bg-primary py-2 px-4 rounded-md">
  Submit
</button>
```

### Responsive Design Standards

**Mobile-First Approach:**
- Desktop is NOT the primary target
- All interfaces must be responsive by default
- Support: Mobile, Tablet, Laptop, Desktop, Ultra-wide screens

**Responsive Behavior:**
When screen size decreases:
1. Compress spacing first
2. Compress padding second
3. Collapse layouts third
4. Stack sections fourth

**Never allow:**
- ❌ Horizontal scrolling
- ❌ Broken layouts
- ❌ Overflowing cards/tables
- ❌ Hidden content

### Component Standards

**Component Rules:**
- Maximum 300 lines per component
- Maximum 10 props per component
- Descriptive naming (e.g., `StudentCard`, not `Card`)
- Single responsibility principle
- Reusable and composable

**Component Structure:**
```
src/components/
├── ui/           # Reusable UI primitives (Button, Input, Card)
├── domain/       # Domain-specific components (StudentCard, CourseTable)
└── layouts/      # Layout components (Header, Sidebar, Footer)
```

### Accessibility Standards

**WCAG AA Minimum:**
- Contrast compliance (4.5:1 for normal text)
- Keyboard navigation support
- Screen reader compatibility
- Visible focus states

**Semantic HTML:**
- Use semantic elements (`<button>`, `<input>`, `<nav>`, etc.)
- Provide ARIA labels when necessary
- All images must have descriptive alt text
- All form inputs must have associated labels

### Typography Standards

**Typeface:** Inter (or system-ui fallback)

**Font Weights:**
- Regular (400): Body text, labels
- Medium (500): Emphasized text, buttons
- Semibold (600): Headings, important labels
- Bold (700): Page titles, strong emphasis

**Font Sizes:**
- xs (12px): Captions, metadata
- sm (14px): Secondary text, labels
- base (16px): Body text, default
- lg (18px): Large body, emphasized text
- xl (20px): Small headings
- 2xl (24px): Section headings
- 3xl (30px): Page titles

### Spacing Standards

**Base Scale:** 4px multiples (0, 4, 8, 12, 16, 20, 24, 32, 40, 48, 64px)

**Component Spacing:**
- Card padding: 16px
- Button padding: 8px vertical, 16px horizontal
- Input padding: 8px vertical, 12px horizontal
- Modal padding: 24px
- Section gap: 24px

**Compact Design:**
- Use compact cards, tables, forms
- Avoid giant padding, huge empty spaces, oversized headers
- Goal: Maximum information with excellent readability

### Layout Standards

**Maximum content width:** 1440px

**Standard page structure:**
1. Header
2. Page Summary
3. Primary Actions
4. Main Content
5. Secondary Content

**Container pattern:**
```html
<div className="w-full max-w-[1440px] mx-auto px-4 md:px-6 lg:px-8">
  Content
</div>
```

### UI Enforcement Checklist

Before generating any UI, verify:

- ✅ Color compliance (Primary + Neutral + Success only)
- ✅ Responsive compliance (mobile-first, no horizontal scroll)
- ✅ Accessibility compliance (WCAG AA minimum)
- ✅ Design token compliance (no hardcoded values)
- ✅ Semantic HTML used
- ✅ Component naming is descriptive
- ✅ Typography follows standards
- ✅ Spacing follows 4px scale
- ✅ Layout within 1440px max width

If a UI violates standards:
- ❌ Refuse implementation
- ❌ Explain the violation
- ✅ Propose a compliant alternative

### Design System Documentation

Complete reference in `docs/design-system/`:
- `design-principles.md` — Platform identity and philosophy
- `color-system.md` — Official color palette and restrictions
- `typography.md` — Typeface, font sizes, weights, hierarchy
- `spacing.md` — Spacing scale and component spacing
- `components.md` — Component system and reusable patterns
- `responsive-rules.md` — Breakpoints and responsive behavior
- `accessibility.md` — WCAG AA compliance requirements

---

## 13. No Mock Data Policy 🚫

**Enforcement Level: CRITICAL — Blocks merge immediately.**

### Rule
Every feature, page, or component must operate exclusively on **real data from the database** via Use Cases and Repositories. Mock data, hardcoded arrays, and faker-generated content have zero place in production code.

### Allowed in Production
```php
// ✅ Real data from Use Case
$result = $this->getDashboardStats->execute(StudentId::of($studentId));
return view('academic.dashboard', ['dashboard' => $result]);

// ✅ Config seeders (lookup tables, academic terms, colleges)
class AcademicTermSeeder extends Seeder { ... }
```

### Allowed in Tests ONLY
```php
// ✅ Factories — ONLY in test files
$student = StudentFactory::new()->withGpa(3.75)->create();

// ✅ Test seeders — ONLY when APP_ENV=testing
class TestUserSeeder extends Seeder { ... }
```

### Forbidden Everywhere
```php
// ❌ Hardcoded data in Blade views
<p>GPA: 3.75</p>
<p>96 / 144 ساعة</p>
@foreach(['مهمة 1', 'مهمة 2'] as $task)

// ❌ Mock arrays in Controllers or Use Cases
return view('dashboard', [
    'gpa' => 3.75,
    'tasks' => ['مراجعة البرمجة'],
]);

// ❌ Faker in production code
$gpa = fake()->randomFloat(2, 2.0, 4.0);
$name = Str::random(8);

// ❌ Hardcoded dates/terms
<p>الفصل الثاني 2026</p>
<p>يونيو 2028</p>
```

### Empty State Requirement
Every data-displaying page MUST handle three states:
1. **Data exists** → render real records from DB
2. **Empty** → styled empty state with CTA
3. **Error** → graceful error message (no stack traces)

---

## 14. Provider-Agnostic Architecture 🔌

**Enforcement Level: HIGH — Required for all external service integrations.**

### Rule
All external service integrations (AI, Email, SMS, Storage, Payment, OAuth) must be abstracted behind Domain Contracts. The service provider's name must never appear outside `Infrastructure/`.

### Correct Structure
```
Domain/Contracts/
├── AiAdvisorInterface.php
├── EmailSenderInterface.php
├── FileStorageInterface.php
└── SmsNotifierInterface.php

Infrastructure/Providers/
├── OpenAiAdvisor.php
├── MailgunEmailSender.php
├── S3FileStorage.php
└── TwilioSmsNotifier.php

App/Providers/
└── ServiceBindingProvider.php    ← all bindings here
```

### Correct Use Case
```php
// ✅ Depends on interface, not concrete provider
final class GenerateStudentInsight
{
    public function __construct(
        private readonly AiAdvisorInterface $aiAdvisor,
        private readonly StudentRepositoryInterface $students,
    ) {}

    public function execute(StudentId $studentId): InsightDto
    {
        $student = $this->students->findById($studentId);
        return $this->aiAdvisor->generateInsight($student);
    }
}
```

### Forbidden
```php
// ❌ Provider SDK in Use Case / Domain
use OpenAI\Client;
use Mailgun\Mailgun;
use Stripe\Stripe;

// ❌ Hardcoded API keys
$client = OpenAI::factory()->withApiKey('sk-abc123');

// ❌ Provider name in UI
<p>Powered by OpenAI</p>
<span>ChatGPT Analysis</span>

// ❌ getenv() in business logic
$key = getenv('OPENAI_API_KEY'); // in a Use Case
```

### Configuration Rules
```ini
# ✅ All provider config in .env
AI_PROVIDER=openai
AI_API_KEY=sk-...
AI_MODEL=gpt-4o-mini

MAIL_PROVIDER=mailgun
MAIL_API_KEY=key-...
```

```php
// ✅ Read only via config()
$model = config('services.ai.model');

// ✅ Binding via ServiceProvider only
$this->app->bind(AiAdvisorInterface::class, fn() => new OpenAiAdvisor(
    apiKey: config('services.ai.key'),
    model: config('services.ai.model'),
));
```

### Provider Switching Checklist
When switching providers (e.g., OpenAI → Gemini):
- [ ] Create new `Infrastructure/Providers/GeminiAdvisor.php` implementing `AiAdvisorInterface`
- [ ] Update binding in `ServiceBindingProvider`
- [ ] Update `.env` values
- [ ] Zero changes to Use Cases, Domain, or Views
- [ ] Zero UI changes required
