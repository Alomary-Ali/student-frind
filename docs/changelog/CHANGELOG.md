# CHANGELOG
## رفيق الطالب — Student Success Platform

---

## [2.0.0] — 2026-06-18 | Security & Governance Remediation

### 🔒 Security — Critical Fixes
- **FIXED:** Added `auth` middleware to ALL academic and productivity routes
- **FIXED:** Added `guest` middleware to login/register routes
- **FIXED:** Added `throttle:5,1` rate limiting on login endpoint
- **FIXED:** Created `LogoutController` with proper session invalidation and CSRF token regeneration
- **FIXED:** Added `POST /logout` route (was completely missing)
- **FIXED:** `APP_DEBUG=false` set as default in `.env.example`
- **FIXED:** `SESSION_ENCRYPT=true` set as default in `.env.example`
- **DELETED:** Dangerous `create_user_raw.php` debug file from project root
- **DELETED:** Dangerous `fix_users_table.php` debug script from project root

### 🏛️ Architecture — Critical Fixes
- **FIXED:** `ProductivityDashboardController` created — was missing entirely (caused crash)
- **FIXED:** `productivity/dashboard.blade.php` converted from standalone HTML to `@extends('layouts.dashboard')` — eliminated layout inconsistency
- **ADDED:** `TenantMiddleware` scaffold registered as `tenant` alias in `bootstrap/app.php`
- **FIXED:** `bootstrap/app.php` — added API JSON error handling and guest redirect logic

### 💾 Database — Critical Fixes
- **ADDED:** `0000_00_00_000000_create_users_table.php` — proper users table migration with UUID PK, academic_id, role enum, status enum
- **ADDED:** `2026_06_20_000009_add_foreign_keys_to_productivity_tables.php` — FK constraints for all productivity tables

### 🎨 UI/UX — High Priority Fixes
- **FIXED:** `layouts/dashboard.blade.php` — unified sidebar added (academic module had no sidebar)
- **FIXED:** Cairo font now loaded from Google Fonts (was missing despite being referenced in CSS)
- **FIXED:** Mobile hamburger menu added — mobile users can now navigate
- **FIXED:** Sidebar overlay for mobile (click-outside-to-close)
- **FIXED:** Logout button added to sidebar with secure POST form

### 🧹 Code Quality
- **FIXED:** `app/Models/User.php` — added `HasApiTokens` trait for Sanctum
- **FIXED:** `app/Models/User.php` — added `'id' => 'string'` cast for UUID
- **FIXED:** `resources/css/app.css` — removed duplicate `:root` color block (was defined twice)
- **FIXED:** `resources/css/app.css` — `warning` color changed to `#F97316` (distinct from `accent` `#F59E0B`)
- **FIXED:** `resources/css/app.css` — RTL hover animation changed from `translateX(-4px)` to `translateX(4px)`
- **ADDED:** `.hover-slide-in` RTL-safe utility class

### 🧪 Testing
- **ADDED:** `tests/Feature/AuthSecurityTest.php` — 10 critical security tests
- **ADDED:** `tests/Feature/DashboardAuthGuardTest.php` — verifies ALL routes require auth
- **FIXED:** `database/factories/UserFactory.php` — rewritten for custom User schema (academic_id, role, status)
- **FIXED:** `phpunit.xml` — all module test suites registered (Productivity, Administration, Analytics)
- **FIXED:** `phpunit.xml` — `src/` directory added to coverage source

### ⚙️ DevOps & CI/CD
- **ADDED:** `.github/workflows/ci.yml` — 7-gate CI pipeline (Pint, PHPStan, Tests, Coverage 80%, Security audit, Dangerous files check, Auth middleware check)
- **ADDED:** `phpstan.neon` — configured at Level 6 covering `app/` and `src/`

### 📚 Documentation
- **ADDED:** `ENGINEERING_RULEBOOK.md` — 10 sections of mandatory engineering governance
- **ADDED:** `docs/architecture/overview.md`
- **ADDED:** `docs/security/pre-release-checklist.md`
- **ADDED:** `docs/api/standards.md`
- **ADDED:** `docs/testing/test-strategy.md`
- **ADDED:** `docs/deployment/production.md`
- **ADDED:** `docs/ui-system/design-system.md`
- **ADDED:** `docs/ui-system/responsive-checklist.md`
- **ADDED:** `docs/decisions/architecture-decisions.md` (ADR-001 → ADR-005)

---

## [2.1.0] — 2026-06-20 | Academic Web Views & Chart Integration

### ✨ New Features — Academic Web Pages
- **ADDED:** `AcademicProfileController` + `profile.blade.php` — صفحة الملف الأكاديمي المتكاملة مع عرض البيانات الشخصية، المؤهل، التخصص، والمعدل التراكمي
- **ADDED:** `AcademicProgressController` + `progress.blade.php` — صفحة مؤشرات الأداء مع 6 بطاقات إحصائية (GPA، الساعات، المستوى، التقدير، الحالة، المقررات)
- **ADDED:** `GraduationMapController` + `graduation-map.blade.php` — خريطة التخرج التفاعلية مع خط زمني مرئي للمستويات الدراسية
- **IMPROVED:** `AcademicPlanController` + `plan.blade.php` — خطة دراسية تفاعلية مع فلترة حسب القسم والحالة، وعرض المقررات ضمن جداول لكل مستوى وفصل

### 🎨 UI/UX
- **ADDED:** Chart.js v4.4.7 (CDN) مع رسمين بيانيين في صفحة مؤشرات الأداء: خطي لتطور GPA وشريطي لمقارنة أداء الفصول
- **ADDED:** روابط التنقل الجديدة (الملف الأكاديمي، مؤشرات الأداء، خريطة التخرج) في القائمة الجانبية
- **IMPROVED:** `plan.blade.php` مع أيقونات حالة ملونة، إحصائيات في الأعلى، و Dark mode دعم كامل

### 🐛 Bug Fixes
- **FIXED:** `GetCurriculumCourses` Query class was missing despite being imported in `ListCurriculumCoursesController` — كان يسبب 500 عند زيارة `/academic/curriculum`
- **FIXED:** `progress.blade.php` view was missing despite route being defined — كان يسبب ViewNotFoundException

---

## [2.2.0] — 2026-06-20 | Productivity Module Enhancement & Gap Closure

### 🐛 Bug Fixes — Missing Views
- **FIXED:** `goals-show.blade.php` كان مفقوداً رغم استيراده في `ProductivityGoalController@show` — يسبب 500 عند زيارة تفاصيل الهدف
- **FIXED:** `tasks-show.blade.php` كان مفقوداً رغم استيراده في `ProductivityTaskController@show` — يسبب 500 عند زيارة تفاصيل المهمة

### ✨ New Enums & Fields
- **ADDED:** `GoalType` enum (daily, semester, long_term) مع حقل `goal_type` في `Goal` entity + DTO + Mapper + Migration
- **ADDED:** `Postponed` case to `TaskStatus` enum + `postpone()` method on `Task` entity (حالة "مؤجل" للمهام)
- **ADDED:** `ReadinessStatus` enum (not_ready, needs_review, partially_ready, fully_ready) مع حقل `readiness_status` في `Exam` entity + Migration + Eloquent model
- **ADDED:** `goalType` badge in `goals.blade.php` list view and `goals-show.blade.php` detail view

### 🎨 UI/UX — Charts & Analytics
- **ADDED:** Chart.js v4.4.7 (CDN) في لوحة الإنتاجية مع رسمين بيانيين: Donut لتوزيع المهام حسب الحالة، و Donut لنسبة الإنجاز الكلية
- **IMPROVED:** `dashboard.blade.php` — إضافة صف الرسوم البيانية بين الإحصائيات والمهام الأخيرة

### 🗄️ Database
- **ADDED:** `2026_06_20_003120_add_goal_type_to_productivity_goals_table.php` — إضافة عمود `goal_type`
- **FIXED:** `2026_06_22_000003_add_readiness_status_to_productivity_exams_table.php` — إعادة تسمية الـ migration لتشغيلها بعد إنشاء الجدول (تمنع `no such table` في SQLite)

### 🎨 UI/UX — Midnight AI Sidebar Redesign
- **REDESIGNED:** الشريط الجانبي بالكامل — تصميم "Midnight AI" داكن فاخر
  - خلفية متدرجة داكنة (`#050B18` → `#030712`) مع touch مظلم Neo-Glass
  - 3 decorative orbs متحركة بخلفية ضبابية (Cyan + Emerald + Indigo)
  - grain texture دقيق جداً (`opacity: 0.03`) يضيف عمقاً ملموساً
  - edge glow جانبي متدرج (Cyan → Emerald → Cyan) يضيء الحافة
  - شعار مع `@keyframes ai-logo-glow` نبضاني (neon pulse 3s)
  - النص "رفيق" في العلامة التجارية بتدرج لوني (Cyan → Emerald)
  - avatar المستخدم مع ring متوهج متحرك (`ai-avatar-ring`)
  - روابط التنقل: hover يكشف glass border مع glow خفيف وتحويل `scale(1.10)` للـ icon
  - الرابط النشط: خلفية زجاجية بتدرج Cyan + border متوهج + indicator جانبي متدرج مع glow
  - أقسام (الأكاديمي/الإنتاجية) مع divider متدرج
  - أزرار الأسفل (theme toggle, logout) مع hover glow
  - نسخة v2.2.0 بتصميم فائق الخفوت
  - تحسين overlay الموبايل مع `backdrop-filter: blur(8px)`

---

## [1.0.0] — 2026-06-15 | Initial Foundation

### Added
- Modular DDD Architecture with 10 modules
- Academic module: Student, Course, Enrollment, Grade, AcademicPlan entities
- Productivity module: Goals, Tasks, Reminders, Calendar
- Shared module: Authentication, Audit Logs
- Design system with Tailwind CSS v4 and Cairo font tokens
- Dark mode support (system + manual toggle)
- Sanctum API routes for Academic module
- Unit tests: StudentEntityTest, GpaTest, GpaCalculationServiceTest
