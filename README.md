<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://via.placeholder.com/1200x200/06214B/FFFFFF?text=%D8%B1%D9%81%D9%8A%D9%82+%D8%A7%D9%84%D8%B7%D8%A7%D9%84%D8%A8">
  <img alt="رفيق الطالب — Student Success Platform" src="https://via.placeholder.com/1200x200/243B7C/FFFFFF?text=%D8%B1%D9%81%D9%8A%D9%82+%D8%A7%D9%84%D8%B7%D8%A7%D9%84%D8%A8">
</picture>

<p align="center">
  <strong>Student Success Platform (SSP)</strong><br>
  <em>Academic Journey • Career Development • Smart Assistant</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Tests-1109-10B981?style=flat-square&logo=phpunit" alt="1109 tests">
  <img src="https://img.shields.io/badge/Coverage-80%25-10B981?style=flat-square" alt="80% coverage">
  <img src="https://img.shields.io/badge/Modules-14-243B7C?style=flat-square" alt="14 modules">
  <img src="https://img.shields.io/badge/Pint-Passing-10B981?style=flat-square" alt="Pint">
  <img src="https://img.shields.io/badge/PHPStan-Level_6-8A2BE2?style=flat-square" alt="PHPStan Level 6">
</p>

---

## 🎯 Vision

A comprehensive platform that accompanies students **from their first day at university** until **graduation and career readiness** — transforming the academic experience into an intelligent, integrated journey.

| For Students | For Universities |
|-------------|-----------------|
| Plan academic journey | Real-time success dashboards |
| Track GPA and performance | At-risk student detection |
| Build skills and portfolio | Cohort analytics |
| Discover jobs & internships | Accreditation reporting |
| AI-powered assistance | Data-driven decisions |

---

## 🧱 Architecture

```
                    ┌─────────────────────────────┐
                    │      Presentation Layer      │
                    │  Controllers · Views · API   │
                    └──────────────┬──────────────┘
                                   │
                    ┌──────────────▼──────────────┐
                    │     Application Layer        │
                    │ Use Cases · DTOs · Mappers   │
                    └──────────────┬──────────────┘
                                   │
                    ┌──────────────▼──────────────┐
                    │       Domain Layer           │
                    │ Entities · VOs · Events      │
                    │   Enums · Contracts          │
                    └──────────────┬──────────────┘
                                   │
                    ┌──────────────▼──────────────┐
                    │    Infrastructure Layer       │
                    │  Eloquent · Repos · Gateways │
                    │   Migrations · Integrations  │
                    └─────────────────────────────┘
```

| Decision | Choice |
|----------|--------|
| **Pattern** | Modular Monolith + Domain-Driven Design |
| **Framework** | Laravel 12 |
| **Language** | PHP 8.2 |
| **Database** | MySQL 8.0+ (SQLite for testing) |
| **Primary Keys** | UUID v4 |
| **Authentication** | Laravel Sanctum |
| **Code Style** | Laravel Pint (PSR-12) |
| **Static Analysis** | PHPStan Level 6 |
| **Testing** | PHPUnit 11 |
| **AI Integration** | OpenAI API (openai-php/laravel) |
| **PDF Generation** | Dompdf (barryvdh/laravel-dompdf) |
| **Module Communication** | Domain Events + Contract Interfaces |

> See [Architecture Decision Records](docs/adr/) for detailed rationale.

---

## 📦 Modules (14)

| # | Module | Files | Purpose | Status |
|---|--------|------:|---------|--------|
| 1 | **Shared** | ~40 | Users, auth, event dispatcher, shared VOs | ✅ Complete |
| 2 | **Academic** | ~80 | Courses, grades, GPA, study plans, graduation | ✅ Complete |
| 3 | **Productivity** | ~70 | Tasks, goals, habits, scheduling | ✅ Complete |
| 4 | **UI** | ~40 | Design system, 14 rf-* components, tokens | ✅ Complete |
| 5 | **Guidance** | ~30 | AI recommendations, early alerts | ✅ Complete |
| 6 | **Skills** | ~60 | Skill profiles, certifications, learning paths | ✅ Complete |
| 7 | **CareerProfile** | ~80 | Portfolio, CV builder, experiences, career goals | ✅ Complete |
| 8 | **Opportunities** | ~60 | Jobs, internships, scholarships, competitions | ✅ Complete |
| 9 | **Analytics** | ~20 | Dashboards, KPIs, university reports | ✅ Complete |
| 10 | **Administration** | ~30 | Roles, permissions, system config | ✅ Complete |
| 11 | **Community** | ~20 | Forums, groups, events, mentorship | ✅ Complete |
| 12 | **Career** | ~97 | Interview prep, career paths, portfolio, dashboard | ✅ Complete |
| 13 | **Notifications** | ~25 | In-app notifications, real-time alerts | ✅ Complete |
| 14 | **StudentServices** | ~174 | Service requests, documents, knowledge base, AI assistant | ✅ Complete |

**Total:** ~999 source files, ~91 views, ~54 migrations, 1109 tests.

---

## ✨ Key Features

### 🎓 Academic Management
- Course enrollment and grade tracking
- GPA calculation and progress monitoring
- Study plan management and graduation mapping
- Academic advising and early alerts

### 💼 Career Development
- Professional portfolio builder
- Skill gap analysis and learning roadmaps
- CV/resume generation
- Interview preparation with AI feedback
- Career path exploration and recommendations
- Employment readiness scoring

### 🤖 Smart Assistant (AI-Powered)
- Natural language question answering
- Knowledge base retrieval
- Service request guidance
- Conversation history and context awareness
- Suggested replies and actions
- Powered by **OpenAI GPT-4o-mini**

### 📄 Document Management
- Request official documents (transcripts, certificates, statements)
- Automated PDF generation with verification codes
- Document verification portal (QR-ready)
- Digital document archive

### 🔔 Notifications
- Real-time in-app notifications
- Service request status updates
- Document availability alerts
- Unread count and mark-as-read

### 📊 Analytics & Insights
- Student success dashboards
- At-risk student identification
- Cohort performance analytics
- Service usage statistics

---

## 🚀 Getting Started

### Prerequisites

```bash
PHP ^8.2
Composer 2.x
MySQL 8.0+ / PostgreSQL 14+
Redis (queues & cache)
Node.js 20+ (Vite build)
```

### Installation

```bash
# Clone
git clone https://github.com/Alomary-Ali/student-frind.git
cd student-frind

# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

# Optional: Seed demo data
php artisan db:seed --class=DatabaseSeeder

# AI Assistant (optional — needs API key)
# Add to .env:
# OPENAI_API_KEY=sk-your-key-here
```

### Development

```bash
# Start the server
php artisan serve

# Compile assets (Vite)
npm run dev
```

### Testing

```bash
# Run full test suite
php artisan test
# or
./vendor/bin/phpunit

# Run module-specific tests
./vendor/bin/phpunit --testsuite=StudentServices-Unit
./vendor/bin/phpunit --testsuite=Career-Unit
```

### Code Quality

```bash
# Code style
./vendor/bin/pint

# Static analysis
./vendor/bin/phpstan analyse
```

---

## 🔌 API Endpoints

The platform exposes RESTful APIs under `auth:sanctum`:

| Prefix | Endpoints |
|--------|-----------|
| `/api/services` | Service catalog |
| `/api/service-requests` | CRUD service requests |
| `/api/documents` | Document management |
| `/api/knowledge` | Knowledge base |
| `/api/faq` | Frequently asked questions |
| `/api/assistant/conversations` | AI chat conversations |

---

## 📁 Project Structure

```
├── AGENTS.md                 # Session memory & progress
├── AI_INSTRUCTIONS.md        # AI coding assistant rules
├── ENGINEERING_RULEBOOK.md   # Mandatory engineering rules
├── app/                      # Laravel application core
├── composer.json             # 14 PSR-4 module namespaces
├── database/
│   ├── factories/            # 13 model factories
│   ├── migrations/           # 54 database migrations
│   └── seeders/              # Data seeders
├── docs/
│   ├── adr/                  # Architecture Decision Records
│   ├── design-system/        # UI tokens, components, guidelines
│   ├── engineering/          # API, database, security, testing rules
│   └── changelog/            # Release history
├── resources/
│   ├── css/                  # Tailwind v4 + design tokens
│   ├── views/                # 91 Blade templates
│   │   ├── components/       # 27 reusable UI components
│   │   ├── layouts/          # Dashboard + Auth layouts
│   │   └── {module}/         # Module-specific views
│   └── lang/                 # Arabic translations
├── src/Modules/
│   ├── {ModuleName}/         # 14 DDD modules
│   │   ├── Domain/           # Entities, VOs, Enums, Events
│   │   ├── Application/      # Use Cases, DTOs, Mappers
│   │   ├── Infrastructure/   # Persistence, Gateways
│   │   ├── Presentation/     # Controllers, Routes, Views
│   │   └── Tests/            # Unit, Feature, Integration
│   └── ...
├── storage/
├── tests/                    # Global test suites
└── vite.config.js            # Tailwind v4 + Laravel Vite
```

---

## 🎨 Design System

Built with **Tailwind CSS v4**, HSL design tokens, and 14 reusable components:

| Component | Description |
|-----------|-------------|
| `x-rf-card` | Content cards with 8 variants |
| `x-rf-button` | Action buttons with 5 variants |
| `x-rf-badge` | Status badges with 6 variants |
| `x-rf-input` | Form inputs with validation |
| `x-rf-modal` | Alpine.js dialog with transitions |
| `x-rf-alert` | Dismissible alerts with 5 levels |
| `x-rf-progress` | Progress bars with ARIA support |
| `x-rf-kpi-card` | KPI metrics with trend indicators |
| `x-rf-empty-state` | Empty state with action button |
| `x-rf-sidebar` | Responsive navigation sidebar |
| `x-rf-bottom-nav` | Mobile bottom navigation |
| `x-rf-breadcrumb` | Breadcrumb navigation |
| `x-rf-dropdown` | Dropdown menus |
| `x-rf-toast` | Toast notifications |

Features: **RTL Arabic** (Cairo font), **Dark mode**, **Responsive**, **WCAG accessible**.

---

## 🧪 Testing Strategy

```
Unit (50%):     Domain entities, VOs, enums, DTOs
Feature (30%):  Use cases, controllers, form requests
Integration:    Cross-module workflows, gateways
API Tests:      Endpoints with Sanctum auth
```

Current: **1109 tests, 3281 assertions, 0 failures** across 137 test files.

---

## 📚 Documentation

| Resource | Location |
|----------|----------|
| Memory System | [`.memory/`](.memory/) |
| Engineering Rules | [`ENGINEERING_RULEBOOK.md`](ENGINEERING_RULEBOOK.md) |
| AI Instructions | [`AI_INSTRUCTIONS.md`](AI_INSTRUCTIONS.md) |
| API Standards | [`docs/engineering/api-standards.md`](docs/engineering/api-standards.md) |
| Security Rules | [`docs/engineering/security-rules.md`](docs/engineering/security-rules.md) |
| Database Rules | [`docs/engineering/database-rules.md`](docs/engineering/database-rules.md) |
| Testing Rules | [`docs/engineering/testing-rules.md`](docs/engineering/testing-rules.md) |
| Coding Rules | [`docs/engineering/coding-rules.md`](docs/engineering/coding-rules.md) |
| Design System | [`docs/design-system/`](docs/design-system/) |
| ADRs | [`docs/adr/`](docs/adr/) |

---

## 🛣️ Roadmap

- [x] **Phase 0-1** — Foundation + Shared Module
- [x] **Phase 2** — Academic Core
- [x] **Phase 3** — Productivity
- [x] **Phase 4** — UI Design System
- [x] **Phase 5** — Career & Skills
- [x] **Phase 6** — Student Services & Smart Assistant
- [ ] **Phase 7** — Community & Mentorship
- [ ] **Phase 8** — Analytics & Administration
- [ ] **Phase 9** — Production Hardening & CI/CD
- [ ] **Phase 10** — Launch & Scale

---

## 🤝 Contribution

1. **Read [`AI_INSTRUCTIONS.md`](AI_INSTRUCTIONS.md)** and **[`ENGINEERING_RULEBOOK.md`](ENGINEERING_RULEBOOK.md)** before writing any code
2. **One use case per class** — no fat controllers or fat models
3. **Domain layer must be framework-free** — no Eloquent, no Facades
4. **Module boundaries are sacred** — use Contracts and Events, never direct imports
5. **Write tests** — every feature needs Unit + Feature tests
6. **Update memory** after every implementation milestone
7. **Every architectural decision** gets logged in `.memory/decisions.md` and an ADR

---

<p align="center">
  <sub>Built with ❤️ for student success —<br>
  <em>من الطالب، للطالب، برفيق الطالب</em>
  </sub>
</p>
