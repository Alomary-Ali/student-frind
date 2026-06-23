# Student Success Platform (SSP)

> A comprehensive academic and career success platform that accompanies students from their first day at university until graduation and career readiness.

---

## Platform Vision

SSP helps students:
- 📚 **Plan** their academic journey
- ✅ **Manage** university life and productivity
- 📊 **Track** academic performance and GPA
- 🤖 **Receive** intelligent guidance and early alerts
- 🛠️ **Build** skills and professional identity
- 💼 **Discover** jobs, internships, and scholarships
- 🤝 **Connect** with mentors and communities
- 🎓 **Prepare** for employment

Universities gain:
- 📈 Real-time student success dashboards
- ⚠️ At-risk student early detection
- 📋 Cohort analytics and reports

---

## Architecture

| Concern              | Choice                                           |
|----------------------|--------------------------------------------------|
| **Pattern**          | Modular Monolith + Domain-Driven Design (DDD)    |
| **Framework**        | Laravel 12 (PHP 8.2+)                            |
| **Layering**         | Clean Architecture (Domain → App → Infra → Presentation) |
| **Communication**    | Domain Events + Contract Interfaces              |
| **Database**         | PostgreSQL (SQLite for dev) — UUID primary keys  |
| **Queue**            | Redis + Laravel Queues                           |
| **Code Quality**     | Laravel Pint + PHPStan                           |
| **Testing**          | PHPUnit — 80%+ coverage target                   |

> See [`docs/adr/`](docs/adr/) for all Architecture Decision Records.

---

## Module Index

| Module            | Purpose                                                        | Status        |
|-------------------|----------------------------------------------------------------|---------------|
| `Shared`          | Users, Auth, Notifications, Files, Audit, Settings            | 🏗️ Scaffold   |
| `Academic`        | Study plans, courses, grades, GPA, graduation                 | 🏗️ Scaffold   |
| `Productivity`    | Tasks, goals, habits, scheduling                               | 🏗️ Scaffold   |
| `Guidance`        | Advising, early alerts, AI recommendations                     | 🏗️ Scaffold   |
| `Skills`          | Skill profiles, competencies, certificates                     | 🏗️ Scaffold   |
| `CareerProfile`   | Portfolio, CV builder, professional identity                   | 🏗️ Scaffold   |
| `Opportunities`   | Jobs, internships, scholarships, competitions                  | 🏗️ Scaffold   |
| `Community`       | Forums, groups, events, mentorship                             | 🏗️ Scaffold   |
| `Analytics`       | Dashboards, KPIs, university reports                           | 🏗️ Scaffold   |
| `Administration`  | System config, multi-tenancy, roles, permissions               | 🏗️ Scaffold   |

---

## Getting Started

### Requirements
- PHP 8.2+
- Composer 2.x
- MySQL 8.0+
- Redis (for queues and cache)

### Installation

```bash
git clone <repository-url>
cd student-success-platform

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Running Locally

```bash
php artisan serve
```

### Running Tests

```bash
php artisan test
```

### Code Style (Pint)

```bash
./vendor/bin/pint
```

---

## Architecture Decision Records

| ADR | Title | Status |
|-----|-------|--------|
| [ADR-001](docs/adr/ADR-001-project-architecture.md) | Modular Monolith Architecture | ✅ Accepted |
| [ADR-002](docs/adr/ADR-002-ddd-rules.md) | DDD Rules and Boundaries | ✅ Accepted |
| [ADR-003](docs/adr/ADR-003-module-boundaries.md) | Module Boundary Enforcement | ✅ Accepted |
| [ADR-004](docs/adr/ADR-004-event-driven-design.md) | Event-Driven Communication | ✅ Accepted |
| [ADR-005](docs/adr/ADR-005-database-conventions.md) | Database Design Conventions | ✅ Accepted |

---

## Memory System

The `.memory/` directory is the **source of truth** for the project.

| File | Purpose |
|------|---------|
| [`project-vision.md`](.memory/project-vision.md) | Mission, users, goals, success metrics |
| [`architecture.md`](.memory/architecture.md) | Architecture rules and layer definitions |
| [`decisions.md`](.memory/decisions.md) | Running log of all architectural decisions |
| [`coding-standards.md`](.memory/coding-standards.md) | Naming rules, size limits, templates |
| [`domain-glossary.md`](.memory/domain-glossary.md) | Canonical domain terminology |
| [`roadmap.md`](.memory/roadmap.md) | Phased development roadmap |
| [`completed-work.md`](.memory/completed-work.md) | Chronological implementation log |
| [`known-issues.md`](.memory/known-issues.md) | Active bugs and technical debt |
| [`future-improvements.md`](.memory/future-improvements.md) | Improvement backlog |

---

## Contribution Guidelines

1. **Read memory** before any task
2. **One use case per class** — no fat controllers or fat models
3. **Domain layer must be framework-free** — no Eloquent, no Facades
4. **Module boundaries are sacred** — use Contracts and Events, never direct imports
5. **Write tests** — every feature needs a Unit + Feature test
6. **Update memory** after every implementation milestone
7. **Every architectural decision** gets logged in `decisions.md` and an ADR if significant

---

*Built with care for student success.*
