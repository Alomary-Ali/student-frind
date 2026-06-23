# Architecture Reference — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Status:** Foundation Phase

---

## Architectural Style

**Pattern:** Modular Monolith
**Design:** Domain-Driven Design (DDD) + Clean Architecture
**Communication:** Event-Driven (within and across modules)
**API:** RESTful JSON API (Laravel)

---

## Why Modular Monolith?

- Simpler deployment than microservices
- Enforces module boundaries without network complexity
- Can be extracted to services later if scale requires
- Single deployment unit = faster iteration in early phases
- See: ADR-001 for full decision rationale

---

## Layer Definitions

### Domain Layer
- Pure PHP — **no Laravel dependencies**
- Contains: Entities, Value Objects, Domain Events, Enums, Specifications, Policies, Contracts, Domain Services
- Must never reference: Eloquent, Facades, HTTP, Cache, Queue

### Application Layer
- Orchestrates domain logic
- Contains: Use Cases, Commands, Queries, DTOs, Actions, Mappers
- May reference: Domain layer, Repository Contracts
- Must NOT contain: SQL queries, HTTP logic, Controller code

### Infrastructure Layer
- Framework-aware implementation
- Contains: Eloquent Models, Migrations, Repository implementations, External API adapters, Cache adapters, Search adapters
- Depends on: Application contracts (Repository interfaces)

### Presentation Layer
- HTTP entry point only
- Contains: Controllers (≤100 lines), Form Requests, API Resources, Route files, HTTP Policies
- Controllers ONLY: validate → call use case → return response

---

## Module Map

```
src/Modules/
├── Academic/         # Study plans, courses, grades, GPA, graduation ✅ IMPLEMENTED
├── Productivity/     # Tasks, goals, habits, scheduling
├── Guidance/         # Advising, AI recommendations, early alerts
├── Skills/           # Competencies, certificates, skill tracking
├── CareerProfile/    # Portfolio, CV builder, personal brand
├── Opportunities/    # Jobs, internships, scholarships, competitions
├── Community/        # Forums, groups, peer connections, events
├── Analytics/        # Dashboards, KPIs, university reports
├── Administration/   # System config, roles, permissions, tenancy
└── Shared/           # Users, Auth, Notifications, Files, Audit, Settings
```

---

## Module Communication Rules

### ALLOWED ✅
- Module A publishes a **Domain Event** → Module B listens and reacts
- Module A calls a **Contract Interface** from Module B's `Domain/Contracts/`
- Module A calls Module B's **Application Service** through a defined interface

### FORBIDDEN ❌
- Module A directly imports Module B's Entity class
- Module A queries Module B's database table directly
- Module A calls Module B's Controller or Eloquent Model
- Cross-module method calls without a Contract interface

---

## Shared Module Rules

The Shared module provides:
- `User` entity (identity only — no roles, no business data)
- Authentication infrastructure
- Authorization (roles & permissions via Policy pattern)
- File storage
- Notification dispatching
- Audit logging
- System settings

The Shared module must NEVER contain business domain logic.

---

## CQRS Application

CQRS is applied where read/write patterns diverge significantly:
- `Commands/` → write operations (create, update, delete)
- `Queries/` → read operations (list, find, report)
- Simple CRUD use cases may use unified UseCases

---

## Event-Driven Design

Domain events are the primary inter-module communication mechanism.

**Naming:** `[Entity][PastTenseVerb]` — e.g., `StudentEnrolled`, `CourseCompleted`, `OpportunityApplied`

**Event Payload:** Must include entity ID, timestamp, and relevant context data. No nested objects — keep payloads flat and serializable.

**Event Bus:** Laravel's built-in Event/Listener system with queue support.

---

## Size Constraints (enforced)

| Unit              | Maximum     |
|-------------------|-------------|
| Class             | 300 lines   |
| Method            | 30 lines    |
| Controller        | 100 lines   |
| Constructor args  | 5           |

Violations must be refactored before merge.

---

## Testing Strategy

| Level       | Scope                          | Tool       | Coverage Target |
|-------------|--------------------------------|------------|-----------------|
| Unit        | Domain entities, value objects | PHPUnit    | 90%+            |
| Feature     | HTTP endpoints (use case flow) | PHPUnit    | 80%+            |
| Integration | Cross-module workflows         | PHPUnit    | Key flows       |

---

## Technology Decisions

| Concern          | Choice                               |
|------------------|--------------------------------------|
| Framework        | Laravel 12 (PHP 8.2+)                |
| Database         | PostgreSQL (recommended) / MySQL     |
| Queue            | Redis + Laravel Queues               |
| Cache            | Redis                                |
| Search           | Laravel Scout + Meilisearch          |
| Code Quality     | Laravel Pint (formatter) + PHPStan   |
| Testing          | PHPUnit / Pest                       |
| API Docs         | Scribe or Scramble                   |

---

## UI Architecture

### Design System Authority

The Student Success Platform operates under a mandatory UI/UX Design Constitution.

**Authority:** Chief Product Designer / Design System Architect
**Enforcement:** All UI must comply with design system standards
**Documentation:** `docs/design-system/`

### Design Philosophy

The platform is:
- Academic, Professional, Intelligent, Calm, Trustworthy, Modern
- NOT: Colorful, Playful, Overdesigned, Corporate-heavy, Government-looking

### Color System

**Primary Color:** `#243B7C` (Deep Blue)
- Logo, main buttons, navigation, active states, links, important actions

**Success Color:** `#10B981` (Emerald Green)
- Progress, completed tasks, success indicators, positive performance

**Neutral Colors:**
- Background: `#F8FAFC`
- Surface: `#FFFFFF`
- Text Primary: `#111827`
- Text Secondary: `#6B7280`
- Borders: `#E5E7EB`

**Color Restrictions:**
- DO NOT introduce additional brand colors
- Platform identity: Primary Blue + Neutral Grays + Success Green
- Status colors (red, orange, yellow) only for indicators when absolutely necessary

**Visual Hierarchy:**
- 80% Neutral Colors
- 15% Primary Color
- 5% Success Color

### Responsive Design

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
- Horizontal scrolling
- Broken layouts
- Overflowing cards/tables
- Hidden content

### Content Density

**Compact Design:**
- Use compact cards, tables, forms
- Avoid giant padding, huge empty spaces, oversized headers
- Goal: Maximum information with excellent readability

### Layout Rules

**Maximum content width:** 1440px

**Standard page structure:**
1. Header
2. Page Summary
3. Primary Actions
4. Main Content
5. Secondary Content

### Component System

**Design Tokens:**
- Typography Tokens
- Spacing Tokens
- Radius Tokens
- Shadow Tokens
- Color Tokens

**All UI must consume tokens. Hardcoded values are forbidden.**

### Accessibility

**WCAG AA Minimum:**
- Ensure contrast compliance
- Keyboard navigation
- Screen reader compatibility
- Visible focus states

### Design System Documentation

Complete design system documentation in `docs/design-system/`:
- `design-principles.md` — Platform identity and philosophy
- `color-system.md` — Official color palette and restrictions
- `typography.md` — Typeface, font sizes, weights, hierarchy
- `spacing.md` — Spacing scale and component spacing
- `components.md` — Component system and reusable patterns
- `responsive-rules.md` — Breakpoints and responsive behavior
- `accessibility.md` — WCAG AA compliance requirements

### UI Enforcement

Before generating any UI, agents must verify:
- ✅ Color compliance (Primary + Neutral + Success only)
- ✅ Responsive compliance (mobile-first, no horizontal scroll)
- ✅ Accessibility compliance (WCAG AA minimum)
- ✅ Design token compliance (no hardcoded values)

If a UI violates standards:
- ❌ Refuse implementation
- ❌ Explain the violation
- ✅ Propose a compliant alternative
