# Architectural Decisions Log

**Project:** Student Success Platform (SSP)
**Format:** Chronological log — newest entries at top

---

## Decision Log

### [2026-06-18] — No Mock Data Policy (Mandatory)
- **Context:** Views contained hardcoded demo data (GPA: 3.75, tasks arrays, semester names). This creates false impressions and masks missing backend functionality.
- **Decision:** All features must operate exclusively on real database data via Use Cases and Repositories. Zero mock data, hardcoded arrays, or faker-generated content in production code.
- **Rationale:** Mock data masks integration gaps, creates false confidence in functionality, and becomes permanent tech debt. Real data forces proper end-to-end implementation.
- **Trade-offs:** Features take longer to build (must implement full stack); empty states must be designed for every page.
- **Status:** Approved — **CRITICAL enforcement level**
- **Affected Rules:** ENGINEERING_RULEBOOK §11, coding-standards §13
- **Allowed exceptions:** Config seeders (lookup tables), Test seeders (APP_ENV=testing only)

---

### [2026-06-18] — Provider-Agnostic External Service Architecture
- **Context:** Future features will integrate AI (recommendations, insights), email notifications, SMS, and cloud storage. Hardcoding provider SDKs in business logic creates vendor lock-in.
- **Decision:** All external service integrations must be abstracted behind Domain Contract interfaces. Provider implementations live exclusively in `Infrastructure/Providers/`. Provider names must never appear in Domain, Application, or Presentation layers.
- **Rationale:** Enables seamless provider switching (e.g., OpenAI → Gemini, Mailgun → SES) without touching business logic or UI. Follows Dependency Inversion Principle.
- **Trade-offs:** More boilerplate (interface + implementation + binding); slightly more indirection.
- **Status:** Approved — **HIGH enforcement level**
- **Affected Rules:** ENGINEERING_RULEBOOK §12, coding-standards §14

---

### [2026-06-18] — Extended Approved Color Palette
- **Context:** Original design system only allowed Primary (#243B7C) + Success (#10B981) + Neutrals. Real UI needs warning states, featured/AI sections, and error indicators.
- **Decision:** Extended the approved palette to include Navy Deep (#06214B), Warning Gold (#F59E0B), and Error Red (#EF4444) with strict usage rules defined in `app.css` design tokens.
- **Rationale:** Navy Deep creates visual hierarchy for premium/AI cards. Warning Gold needed for GPA, deadlines, attention items. Error Red for destructive actions only. All defined as CSS custom properties for consistency.
- **Trade-offs:** More colors to manage; requires discipline to maintain visual hierarchy ratios (75% neutral, 15% primary, 5% accent, 3% warning, 2% navy).
- **Status:** Approved
- **Visual Hierarchy:** 75% Neutral → 15% Primary → 5% Accent → 3% Warning → 2% Navy
- **Documentation:** Updated in ENGINEERING_RULEBOOK §7, coding-standards §12, `app.css` @theme block

---

### [2026-06-20] — MySQL as Primary Database
- **Context:** SQLite was configured as default database; not suitable for production deployment
- **Decision:** MySQL 8.0+ configured as primary database for all environments
- **Rationale:** MySQL provides production-ready features, concurrent writes, full-text search, proper constraints, and ACID compliance. Widely supported and well-documented.
- **Trade-offs:** PostgreSQL offers advanced features (UUID support, JSON columns, full-text search) but MySQL is sufficient for current requirements
- **Status:** Approved

### [2026-06-16] — Academic Module Domain ReadModels for Cross-Module Contracts
- **Context:** `AcademicPlanReaderInterface` initially referenced Application DTOs from Domain layer, violating dependency rule
- **Decision:** Introduced `Domain/ReadModels/` (StudentAcademicProfile, AcademicPlanSummary, GraduationProgress) as contract return types
- **Rationale:** Keeps Domain layer independent of Application; Application maps ReadModels to DTOs when needed for API
- **Trade-offs:** Slight duplication between ReadModels and DTOs
- **Status:** Approved

---

### [2026-06-16] — Academic Student Profile Separate from Shared User
- **Context:** Glossary defines User (Shared) vs Student (academic actor). Student academic data must not live in Shared module
- **Decision:** `academic_students` table links to `users.id` via FK; Academic module owns all academic entities
- **Rationale:** Module boundary compliance (ADR-003); User remains identity-only
- **Trade-offs:** Requires join at application level for full student view
- **Status:** Approved

---

### [2026-06-16] — Adopted Mandatory UI/UX Design Constitution
- **Context:** Need to establish a unified visual system across the entire platform to ensure consistency, professionalism, and academic credibility
- **Decision:** Implemented a comprehensive Design Constitution with mandatory compliance for all UI/UX work
- **Rationale:** Ensures consistent visual identity, reduces design debt, improves user experience, and maintains professional academic appearance across all modules
- **Trade-offs:** Requires strict discipline and enforcement; limits creative freedom but ensures cohesion
- **Status:** Approved
- **Documentation:** `docs/design-system/` (7 comprehensive documents)
- **Key Elements:**
  - Color system: Primary Blue (#243B7C) + Neutral Grays + Success Green (#10B981)
  - Mobile-first responsive design (desktop is NOT primary target)
  - Compact content density for academic data
  - WCAG AA accessibility compliance
  - Design token system (no hardcoded values)
  - Component-based architecture
- **Enforcement:** All UI must pass compliance checklist before implementation

---

### [2026-06-15] — Selected Laravel 12 as Application Framework
- **Context:** Need a PHP framework supporting modular structure, DDD patterns, robust ORM, and large ecosystem
- **Decision:** Laravel 12 (latest compatible with PHP 8.2)
- **Rationale:** Mature ecosystem, strong community, built-in queue/cache/event systems, excellent DI container
- **Trade-offs:** Framework coupling at infrastructure layer (acceptable — domain remains pure PHP)
- **Status:** Approved
- **ADR:** ADR-001

---

### [2026-06-15] — Modular Monolith over Microservices
- **Context:** Need to balance team size (early stage), deployment simplicity, and module isolation
- **Decision:** Modular Monolith with strict module boundaries enforced by code conventions
- **Rationale:** Single deployment unit reduces operational overhead; module isolation maintained through DDD patterns; can be decomposed later if needed
- **Trade-offs:** Requires strict discipline; harder to enforce boundaries without tooling
- **Status:** Approved
- **ADR:** ADR-001

---

### [2026-06-15] — UUID Primary Keys for All Tables
- **Context:** Distributed ID generation, privacy, and future service decomposition concerns
- **Decision:** UUID v4 for all primary keys across all modules
- **Rationale:** No sequential ID exposure, globally unique, works if tables are eventually split across services
- **Trade-offs:** Slightly larger index size vs. integers; slightly slower for sequential scans
- **Status:** Approved
- **ADR:** ADR-005

---

### [2026-06-15] — Domain Layer Must Be Framework-Free
- **Context:** Long-term maintainability and testability of business logic
- **Decision:** Domain layer (Entities, Value Objects, Domain Services, Events) contains zero Laravel/Eloquent imports
- **Rationale:** Framework independence ensures domain logic is testable without bootstrapping the app; enables future framework migration if needed
- **Trade-offs:** More boilerplate (manual mapping between domain objects and Eloquent models)
- **Status:** Approved
- **ADR:** ADR-002

---

### [2026-06-15] — Event-Driven Inter-Module Communication
- **Context:** Modules must not directly depend on each other's internals
- **Decision:** Modules communicate exclusively via Domain Events and Contract interfaces
- **Rationale:** Loose coupling, enables async processing, allows easy addition of listeners without modifying publishers
- **Trade-offs:** Harder to trace code flow; requires good event naming discipline
- **Status:** Approved
- **ADR:** ADR-004

---

### [2026-06-15] — src/Modules/ as Module Root Directory
- **Context:** Need to organize module code separately from Laravel's default structure
- **Decision:** All domain modules live in `src/Modules/{ModuleName}/`; Laravel's default `app/` retained for bootstrapping and Shared only
- **Rationale:** Clear separation; PSR-4 autoloading via composer.json; explicit module boundaries visible in file system
- **Trade-offs:** Requires custom autoloading configuration
- **Status:** Approved

---

*Template for new decisions:*
```
### [YYYY-MM-DD] — [Decision Title]
- **Context:** Why was this decision needed?
- **Decision:** What was decided?
- **Rationale:** Why this option was chosen
- **Trade-offs:** What are the downsides or risks?
- **Status:** Proposed | Approved | Superseded
- **ADR:** ADR-XXX (if formal ADR created)
```
