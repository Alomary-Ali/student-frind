# Development Roadmap — Student Success Platform (SSP)

**Last Updated:** 2026-06-15
**Status:** Phase 0 — In Progress

---

## Phase Overview

| Phase | Name                  | Status      | Duration   |
|-------|-----------------------|-------------|------------|
| 0     | Foundation            | ✅ In Progress | 2 weeks  |
| 1     | Core Identity         | ⏳ Pending  | 3 weeks    |
| 2     | Academic Core         | ⏳ Pending  | 4 weeks    |
| 3     | Productivity          | ⏳ Pending  | 3 weeks    |
| 4     | Career Foundation     | ⏳ Pending  | 4 weeks    |
| 5     | Opportunities         | ⏳ Pending  | 3 weeks    |
| 6     | Guidance & AI         | ⏳ Pending  | 5 weeks    |
| 7     | Community             | ⏳ Pending  | 4 weeks    |
| 8     | Analytics             | ⏳ Pending  | 4 weeks    |
| 9     | Administration        | ⏳ Pending  | 3 weeks    |
| 10    | Launch & Scale        | ⏳ Pending  | Ongoing    |

---

## Phase 0 — Foundation
**Goal:** Establish enterprise-grade architecture skeleton. No business features.

**Key Deliverables:**
- [x] Laravel 12 project initialized
- [x] PSR-4 module autoloading configured in composer.json
- [x] 10 module folder structures (Domain/Application/Infrastructure/Presentation/Tests)
- [x] 10 Module ServiceProviders created
- [x] Central ModuleServiceProvider registered in bootstrap/providers.php
- [x] .memory/ system established (9 files)
- [x] docs/adr/ system established (5 ADRs)
- [x] Coding standards documented
- [x] Domain glossary defined
- [x] Laravel Pint configured
- [ ] PHPStan Level 6 baseline established
- [ ] GitHub Actions CI pipeline configured

**Dependencies:** None
**Success Criteria:** `php artisan` runs cleanly; all modules discoverable; coding standards enforced.

---

## Phase 1 — Core Identity (Shared Module)
**Goal:** Users can register, log in, and have roles assigned.

**Key Deliverables:**
- User entity (UUID, email, name, role)
- Authentication (Laravel Sanctum tokens)
- Authorization (Role-based: Student, Advisor, Admin, Employer, Mentor)
- Email verification + Password reset
- File storage abstraction (S3-compatible)
- Audit log service
- Notification dispatching (email + database)
- System settings repository

**Dependencies:** Phase 0
**Duration:** 3 weeks
**Success Criteria:** Students and admins can authenticate; roles are enforced; audit log captures changes.

---

## Phase 2 — Academic Core (Academic Module)
**Goal:** Students can manage their academic journey.

**Key Deliverables:**
- Academic Plan management
- Course catalog + Course Enrollment (with business rules)
- Grade recording + GPA calculation (cumulative + semester)
- Academic Standing computation
- Graduation Progress tracking
- Early warning: GPA below threshold event

**Dependencies:** Phase 1
**Duration:** 4 weeks
**Success Criteria:** Student can see plan, enrolled courses, GPA, and graduation progress.

---

## Phase 3 — Productivity (Productivity Module)
**Goal:** Students can organize their time and goals.

**Key Deliverables:**
- Task management (title, due date, priority, status)
- Goal setting (SMART goal framework)
- Daily/weekly Schedule builder
- Habit tracker
- Progress analytics for tasks and goals

**Dependencies:** Phase 1
**Duration:** 3 weeks
**Success Criteria:** Student can create tasks, set goals, and track habits within a weekly schedule.

---

## Phase 4 — Career Foundation (Skills + CareerProfile Modules)
**Goal:** Students build a professional identity.

**Key Deliverables:**
- Skill Profile + Competency framework (Technical, Soft, Domain skills)
- Certificate upload and management
- Career Profile (bio, photo, social links)
- Portfolio with Portfolio Items (projects, achievements)
- CV data structure and PDF export
- Public profile URL

**Dependencies:** Phase 1, Phase 2
**Duration:** 4 weeks
**Success Criteria:** Student has a complete, shareable career profile.

---

## Phase 5 — Opportunities (Opportunities Module)
**Goal:** Students discover and apply to relevant opportunities.

**Key Deliverables:**
- Opportunity model (Job, Internship, Scholarship, Competition)
- Employer profile and posting management
- Opportunity search, filter, and application flow
- Application status tracking
- Skill-based opportunity matching
- Saved opportunities

**Dependencies:** Phase 1, Phase 4
**Duration:** 3 weeks
**Success Criteria:** Student can apply to opportunities; employer can post and review applications.

---

## Phase 6 — Guidance & AI (Guidance Module)
**Goal:** Students receive proactive, intelligent guidance.

**Key Deliverables:**
- Advising Session scheduling and recording
- Early Alert system (GPA drop, missed tasks, attendance)
- AI-powered course and opportunity recommendations
- Advisor dashboard (at-risk students view)
- Guidance notes and action plans

**Dependencies:** Phase 2, Phase 3, Phase 4, Phase 5
**Duration:** 5 weeks
**Success Criteria:** Advisors notified of at-risk students; students receive relevant recommendations.

---

## Phase 7 — Community (Community Module)
**Goal:** Students connect with peers and mentors.

**Key Deliverables:**
- Community Groups (create, join, manage)
- Discussion Forums (threaded posts, replies)
- Event creation and RSVP
- Peer connection requests
- Mentorship program (student-mentor matching and session scheduling)

**Dependencies:** Phase 1
**Duration:** 4 weeks
**Success Criteria:** Students can join groups, participate in forums, and connect with mentors.

---

## Phase 8 — Analytics (Analytics Module)
**Goal:** Universities make data-driven decisions.

**Key Deliverables:**
- Student success KPI dashboard
- Cohort analysis (graduation rates, GPA trends)
- At-risk student heatmap
- Opportunity conversion rates
- Custom report builder + Data export (CSV, Excel)

**Dependencies:** All domain modules
**Duration:** 4 weeks
**Success Criteria:** Admin can view real-time dashboards and export reports.

---

## Phase 9 — Administration (Administration Module)
**Goal:** Platform is configurable and multi-tenant-ready.

**Key Deliverables:**
- University (Tenant) management
- System configuration panel
- Role and permission management UI
- User management (CRUD, bulk import)
- Module enable/disable per tenant
- Audit log viewer

**Dependencies:** Phase 1, Phase 8
**Duration:** 3 weeks
**Success Criteria:** Each university has an isolated tenant; admins can configure the platform.

---

## Phase 10 — Launch & Scale
**Goal:** Production-ready platform.

**Key Deliverables:**
- Performance profiling and optimization
- Redis caching strategy
- Meilisearch optimization
- API rate limiting and security audit
- Load testing + CI/CD finalization
- Mobile application (React Native or Flutter) — future
- Multi-language support (Arabic/English)

**Dependencies:** All phases
**Duration:** Ongoing
