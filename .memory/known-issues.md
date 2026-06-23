# Known Issues & Technical Debt — SSP

**Last Updated:** 2026-06-18

---

## Active Issues

| ID     | Date       | Severity | Status   | Description                                                          |
|--------|------------|----------|----------|----------------------------------------------------------------------|
| KI-001 | 2026-06-15 | Low      | Open     | PHP 8.2 installed; PHP 8.3 required for Laravel 13+                 |
| KI-002 | 2026-06-15 | Low      | Resolved | SQLite → MySQL 8.0+ now configured for all environments             |
| KI-003 | 2026-06-15 | Medium   | Open     | PHPStan not yet configured — no static analysis running              |
| KI-004 | 2026-06-15 | Medium   | Open     | No CI/CD pipeline (GitHub Actions) — rules not auto-enforced        |
| KI-005 | 2026-06-15 | Low      | Open     | No `.editorconfig` file for consistent IDE settings                  |
| KI-006 | 2026-06-18 | High     | Resolved | 7 views were standalone HTML — now use `@extends('layouts.dashboard')` |
| KI-007 | 2026-06-18 | Critical | Resolved | Academic dashboard had hardcoded mock data — replaced with real DTOs |
| KI-008 | 2026-06-18 | Medium   | Open     | No automated check prevents mock data or standalone pages from merging |
| KI-009 | 2026-06-18 | Medium   | Open     | Provider-Agnostic interfaces not yet created (no external services yet) |

---

## Issue Details

### KI-001 — PHP Version
- **Severity:** Low
- **Description:** PHP 8.2.12 is installed via XAMPP. Laravel 13+ will require PHP 8.3+. Current Laravel 12 is compatible.
- **Resolution:** Upgrade PHP to 8.3 before attempting Laravel 13 upgrade. Use `php --version` to verify.
- **Blocker for:** Phase 10 (Launch & Scale)

### KI-002 — SQLite in Development
- **Severity:** Low
- **Status:** Resolved
- **Description:** Laravel was initialized with SQLite. MySQL 8.0+ is now configured as the default database for all environments.
- **Resolution:** Updated `.env.example` and `config/database.php` to use MySQL as default connection.
- **Blocker for:** None

### KI-003 — No Static Analysis
- **Severity:** Medium
- **Description:** PHPStan is listed in `composer.json` require-dev but not yet installed or configured. No `phpstan.neon` exists.
- **Resolution:** Run `composer require --dev phpstan/phpstan`, create `phpstan.neon` with Level 6 baseline.
- **Blocker for:** Code quality enforcement

### KI-004 — No CI/CD Pipeline
- **Severity:** Medium
- **Description:** No automated testing pipeline. Code can be merged without running tests or static analysis.
- **Resolution:** Create `.github/workflows/ci.yml` with: PHP lint, Pint check, PHPStan, PHPUnit test suite.
- **Blocker for:** Team collaboration

### KI-005 — No EditorConfig
- **Severity:** Low
- **Description:** No `.editorconfig` file to enforce consistent whitespace/encoding across editors.
- **Resolution:** Create `.editorconfig` with: indent_style=space, indent_size=4, end_of_line=lf, charset=utf-8.

### KI-006 — Standalone HTML Pages (Resolved)
- **Date:** 2026-06-18
- **Severity:** High
- **Status:** Resolved
- **Description:** 7 productivity and academic views (`goals`, `tasks`, `calendar`, `reminders`, `courses`, `productivity/dashboard`, `academic/dashboard`) were full standalone HTML documents with their own `<body>`, `<head>`, and duplicate sidebars — directly violating ENGINEERING_RULEBOOK §7.
- **Resolution:** All views rewritten to use `@extends('layouts.dashboard')` with `@section('content')`. Sidebar and top bar now managed exclusively by the layout.
- **Blocker for:** N/A (Resolved)

### KI-007 — Mock/Hardcoded Data in Academic Dashboard (Resolved)
- **Date:** 2026-06-18
- **Severity:** Critical
- **Status:** Resolved
- **Description:** `academic/dashboard.blade.php` contained hardcoded demo values: GPA `3.75`, credit hours `96/144`, semester name, task lists, milestone labels, and AI insight text — all static strings violating ENGINEERING_RULEBOOK §11.
- **Resolution:** View rewritten to use `@if/$data->count()` patterns with proper empty states. Data must come from Use Case DTOs passed by the Controller.
- **Blocker for:** N/A (Resolved)

### KI-008 — No Automated Rulebook Enforcement
- **Date:** 2026-06-18
- **Severity:** Medium
- **Status:** Open
- **Description:** The engineering rulebook (§7, §11, §12) has no automated enforcement. A developer can add mock data, standalone pages, or hardcoded provider names without CI catching it.
- **Resolution:** Create `.github/workflows/ci.yml` with custom checks:
  1. Grep for `<!DOCTYPE html>` in `resources/views/` subdirectories (flags standalone pages)
  2. PHPStan custom rules for provider SDK imports in Domain/Application
  3. Pint for code style
- **Blocker for:** Team scaling, reliable code quality

### KI-009 — Provider-Agnostic Interfaces Not Created Yet
- **Date:** 2026-06-18
- **Severity:** Medium
- **Status:** Open
- **Description:** ENGINEERING_RULEBOOK §12 mandates Domain Contract interfaces for all external services. No external services are integrated yet, so no interfaces exist. When first AI/Email/SMS feature is built, the pattern must be followed from day one.
- **Resolution:** When first external service is integrated, create `Domain/Contracts/{ServiceName}Interface.php` before writing any Infrastructure code. See rulebook §12 for template.
- **Blocker for:** AI recommendation engine, email notifications, SMS alerts

---

## Issue Template

```markdown
### KI-XXX — [Title]
- **Date:** YYYY-MM-DD
- **Severity:** Low | Medium | High | Critical
- **Status:** Open | In Progress | Resolved
- **Description:** What is the problem?
- **Resolution:** How to fix it?
- **Blocker for:** Which phase or feature does this block?
```
