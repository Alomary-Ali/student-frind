# Architecture Overview
## رفيق الطالب — Student Success Platform

---

## نظرة عامة

منصة SaaS جامعية مبنية على **Modular DDD Monolith** مع Laravel 12 / PHP 8.2.

```
┌─────────────────────────────────────────────────────┐
│                   Presentation Layer                │
│  Controllers • Blade Views • API Resources • Routes │
├─────────────────────────────────────────────────────┤
│                  Application Layer                  │
│       Use Cases • Commands • DTOs • Queries         │
├─────────────────────────────────────────────────────┤
│                    Domain Layer                     │
│   Entities • Value Objects • Events • Interfaces   │
├─────────────────────────────────────────────────────┤
│                Infrastructure Layer                 │
│  Eloquent Models • Repositories • External APIs    │
└─────────────────────────────────────────────────────┘
```

---

## الوحدات (Modules)

| الوحدة | الحالة | الوصف |
|--------|--------|-------|
| **Academic** | ✅ Core | إدارة المسار الأكاديمي، المواد، التسجيل، الدرجات |
| **Productivity** | 🔨 WIP | الأهداف، المهام، التذكيرات، التقويم |
| **Shared** | ✅ Core | المصادقة، المستخدمون، الأحداث المشتركة |
| **Administration** | 📋 Planned | إدارة الجامعة، المستخدمين، الإعدادات |
| **Analytics** | 📋 Planned | تقارير أداء الطلاب |
| **Guidance** | 📋 Planned | الإرشاد الأكاديمي |
| **CareerProfile** | 📋 Planned | ملف المهنة والمهارات |
| **Opportunities** | 📋 Planned | الفرص الوظيفية |
| **Community** | 📋 Planned | مجتمع الطلاب |
| **Skills** | 📋 Planned | اختبارات وتطوير المهارات |

---

## هيكل الملفات

```
src/Modules/{Module}/
├── Domain/
│   ├── Entities/          — Rich Domain Entities (final class)
│   ├── ValueObjects/      — Immutable VOs (readonly)
│   ├── Events/            — Domain Events
│   ├── Exceptions/        — Domain-specific Exceptions
│   ├── Contracts/         — Repository & Service Interfaces
│   ├── Services/          — Domain Services (stateless logic)
│   ├── Enums/             — PHP 8.1+ Enums
│   ├── Specifications/    — Business Rule Specifications
│   └── Policies/          — Authorization Policies
│
├── Application/
│   ├── UseCases/          — Single-responsibility Use Cases
│   ├── Commands/          — Write commands
│   ├── Queries/           — Read queries
│   ├── DTOs/              — Data Transfer Objects (readonly)
│   └── Mappers/           — Domain ↔ DTO mapping
│
├── Infrastructure/
│   ├── Persistence/
│   │   ├── Eloquent{Entity}.php  — Eloquent Models
│   │   └── Migrations/           — Module-specific migrations
│   ├── Repositories/      — Eloquent implementations of Contracts
│   ├── Audit/             — Audit logging implementations
│   ├── Cache/             — Caching strategies
│   └── Notifications/     — Email/SMS/Push notifications
│
├── Presentation/
│   ├── Controllers/       — HTTP Controllers (final, single-action)
│   ├── Requests/          — Form Request validation
│   ├── Resources/         — API Resource transformers
│   ├── Routes/            — api.php (module API routes)
│   └── Policies/          — Route-level authorization
│
└── Tests/
    ├── Unit/              — Pure PHPUnit tests
    ├── Feature/           — HTTP-level tests
    └── Integration/       — Repository + DB tests
```

---

## Data Flow — Grade Recording

```
HTTP POST /api/v1/academic/records
    ↓
RecordGradeController (validates via RecordGradeRequest)
    ↓
RecordAcademicGrade::execute(RecordGradeDto)
    ↓
  [Transaction] 
  EnrollmentRepository::findById()
  StudentRepository::findById()
  AcademicRecord::record()           ← Domain Entity
  GpaCalculationService::calculate()  ← Domain Service
  student->updateGpa()               ← Domain Method
  GraduationPath::updateProgress()   ← Domain Entity
  EventDispatcher::dispatch()        ← Domain Events
  AuditLogger::log()                 ← Audit Trail
    ↓
ApiResponse::success(result)
```

---

## Multi-Tenancy Strategy

**الحالة الراهنة:** `institution_id` موجود في `academic_students`

**المرحلة القادمة:** TenantMiddleware يحل الـ institution_id ويربطه بـ IoC Container، وكل Repository يطبق Global Scope تلقائياً.

```php
// المستقبل
class EloquentStudentRepository implements StudentRepositoryInterface
{
    public function all(): Collection
    {
        return EloquentStudent::forTenant(app('tenant.id'))->get();
    }
}
```
