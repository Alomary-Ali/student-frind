# Future Improvements Backlog — SSP

**Last Updated:** 2026-06-15

---

## Backlog

| ID    | Priority | Category         | Title                                          |
|-------|----------|------------------|------------------------------------------------|
| FI-001 | High    | Infrastructure   | Upgrade to PHP 8.3 + Laravel 13                |
| FI-002 | High    | Code Quality     | PHPStan Level 8 full enforcement               |
| FI-003 | High    | DevOps           | GitHub Actions CI/CD pipeline                  |
| FI-004 | Low     | Database         | Migrate from MySQL to PostgreSQL (optional)    |
| FI-005 | Medium  | Architecture     | Event Sourcing for Analytics module            |
| FI-006 | Medium  | Feature          | Real-time notifications (Laravel Reverb)       |
| FI-007 | Medium  | Feature          | AI recommendation engine (OpenAI integration) |
| FI-008 | Medium  | Platform         | Mobile application (React Native or Flutter)  |
| FI-009 | Medium  | Internationalization | Multi-language support (Arabic/English)   |
| FI-010 | Medium  | Security         | API rate limiting and throttling strategy      |
| FI-011 | Medium  | Architecture     | Multi-tenancy with strict university isolation |
| FI-012 | Low     | Accessibility    | WCAG 2.1 AA compliance audit                  |
| FI-013 | Low     | Feature          | GraphQL API layer (Apollo/Lighthouse)          |
| FI-014 | Low     | Infrastructure   | Container orchestration (Docker + Kubernetes)  |
| FI-015 | Low     | Monitoring       | Application Performance Monitoring (APM)       |

---

## Detailed Descriptions

### FI-001 — Upgrade PHP 8.3 + Laravel 13
- **Priority:** High
- **Category:** Infrastructure
- **Description:** PHP 8.3 unlocks typed class constants, improved `json_validate()`, and better performance. Laravel 13 brings new features and long-term support.
- **Prerequisites:** Verify all custom code is PHP 8.3 compatible. Run tests after upgrade.

### FI-002 — PHPStan Level 8 Enforcement
- **Priority:** High
- **Category:** Code Quality
- **Description:** PHPStan Level 8 is the strictest analysis level. Catches type errors, null pointer issues, and incorrect method calls at compile time — eliminating an entire class of runtime bugs.
- **Prerequisites:** Start at Level 6 baseline, progressively raise to 8. Fix all violations.

### FI-003 — GitHub Actions CI/CD
- **Priority:** High
- **Category:** DevOps
- **Description:** Automated pipeline on every PR: PHP CS Fixer, Pint formatting, PHPStan analysis, PHPUnit test suite, code coverage badge.
- **Prerequisites:** Repository hosted on GitHub.

### FI-004 — PostgreSQL Migration (Optional)
- **Priority:** Low
- **Category:** Database
- **Description:** MySQL 8.0+ is currently the default database. PostgreSQL provides additional features like UUID support, full-text search, JSON columns, and advanced indexing. Migration is optional and can be done if PostgreSQL features are needed.
- **Prerequisites:** PostgreSQL server available. Update `.env` and `config/database.php`.

### FI-005 — Event Sourcing for Analytics
- **Priority:** Medium
- **Category:** Architecture
- **Description:** The Analytics module would benefit from event sourcing — storing state changes as immutable events rather than current state. Enables temporal queries, audit trails, and replaying history.
- **Prerequisites:** Complete Analytics module v1. Evaluate `spatie/laravel-event-sourcing`.

### FI-006 — Real-Time Notifications (Reverb)
- **Priority:** Medium
- **Category:** Feature
- **Description:** Laravel Reverb provides a self-hosted WebSocket server. Enables real-time: chat, notification badges, live dashboards, instant advisor alerts.
- **Prerequisites:** Phase 1 (notifications foundation), Redis.

### FI-007 — AI Recommendation Engine
- **Priority:** Medium
- **Category:** Feature
- **Description:** Integrate OpenAI API or a local LLM (Ollama) to power intelligent course recommendations, opportunity matching, and personalized study advice. Initially rule-based; evolve to ML-powered.
- **Prerequisites:** Phase 6 (Guidance module), student data accumulated, API key.

### FI-008 — Mobile Application
- **Priority:** Medium
- **Category:** Platform
- **Description:** A React Native or Flutter mobile app for students. Push notifications, offline task management, mobile-first UX. API must be stable and versioned before mobile development begins.
- **Prerequisites:** Phase 5+ complete, API v1 stable and documented.

### FI-009 — Multi-Language Support (Arabic/English)
- **Priority:** Medium
- **Category:** Internationalization
- **Description:** Full Arabic RTL support alongside English. Laravel's localization system (`lang/` files). All UI strings externalized. Date/number formatting per locale.
- **Prerequisites:** Frontend framework decided. All string literals extracted from PHP to lang files.

### FI-010 — API Rate Limiting
- **Priority:** Medium
- **Category:** Security
- **Description:** Per-user and per-IP rate limits on all API endpoints using Laravel's throttle middleware. Prevents abuse and protects performance under load.
- **Prerequisites:** Authentication (Phase 1). Redis for rate limit storage.

### FI-011 — Multi-Tenancy
- **Priority:** Medium
- **Category:** Architecture
- **Description:** Full university isolation: each university's data is logically (or physically) separated. Students from University A cannot access University B data. Evaluate `stancl/tenancy` package.
- **Prerequisites:** Administration module (Phase 9). Database strategy decision (single DB with tenant_id vs. separate schemas).

### FI-012 — WCAG 2.1 AA Accessibility
- **Priority:** Low
- **Category:** Accessibility
- **Description:** Ensure all UI components meet Web Content Accessibility Guidelines Level AA. Screen reader support, keyboard navigation, sufficient color contrast.
- **Prerequisites:** Frontend framework and components decided.

### FI-013 — GraphQL API
- **Priority:** Low
- **Category:** Feature
- **Description:** Add GraphQL layer via `nuwave/lighthouse` for flexible querying — especially useful for the Analytics and mobile use cases where clients want specific data shapes.
- **Prerequisites:** REST API v1 fully stable. Lighthouse evaluation complete.

### FI-014 — Container Orchestration
- **Priority:** Low
- **Category:** Infrastructure
- **Description:** Dockerize the application (PHP-FPM + Nginx + Redis + PostgreSQL). Kubernetes deployment manifests for cloud-native scaling.
- **Prerequisites:** Application stable, load testing complete.

### FI-015 — APM Integration
- **Priority:** Low
- **Category:** Monitoring
- **Description:** Integrate Application Performance Monitoring (e.g., Sentry, Datadog, or Laravel Telescope + Pulse) to track errors, slow queries, queue health, and user experience metrics.
- **Prerequisites:** Production deployment.
