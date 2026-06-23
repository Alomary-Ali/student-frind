# ADR-001: Modular DDD Architecture
**التاريخ:** 2026-06-15 | **الحالة:** مقبول

## السياق
المشروع منصة جامعية Enterprise تخدم عشرات الجامعات. نحتاج بنية تسمح بـ:
- تطوير وحدات مستقلة
- اختبار كل وحدة بمعزل
- استبدال infrastructure بدون تغيير domain
- توسع horizontal مستقبلاً

## القرار
اعتماد **Domain-Driven Design (DDD)** مع **Modular Monolith** هيكل.

```
src/Modules/{ModuleName}/
├── Domain/          — Entities, VOs, Events, Interfaces
├── Application/     — Use Cases, DTOs, Commands, Queries
├── Infrastructure/  — Eloquent, Repositories, External APIs
└── Presentation/    — Controllers, Requests, Resources, Routes
```

## العواقب
- ✅ Separation of concerns واضحة
- ✅ قابلية اختبار عالية
- ✅ يمكن استخراج كل Module لـ Microservice مستقبلاً
- ⚠️ يحتاج discipline من الفريق للالتزام بالحدود

---

# ADR-002: UUID كـ Primary Keys
**التاريخ:** 2026-06-15 | **الحالة:** مقبول

## القرار
كل جداول قاعدة البيانات تستخدم UUID كـ primary key.

## الأسباب
- لا يمكن تخمين IDs (IDOR protection)
- مناسب للـ Multi-tenancy
- يعمل مع Distributed systems مستقبلاً
- يمنع sequential ID enumeration attacks

---

# ADR-003: Academic ID كـ Auth Identifier
**التاريخ:** 2026-06-15 | **الحالة:** مقبول

## القرار
المصادقة تتم بـ `academic_id` (8 أرقام) بدلاً من email.

## الأسباب
- الجامعات تستخدم الرقم الجامعي كمعرّف أساسي
- يتطابق مع أنظمة الجامعة الموجودة
- أسهل على الطلاب (لا يحتاجون تذكر email)

## التطبيق
```php
// User model
public function getAuthIdentifierName(): string
{
    return 'academic_id';
}
public function getAuthPassword(): string
{
    return $this->password_hash;
}
```

---

# ADR-004: Session-Based Auth للـ Web + Sanctum للـ API
**التاريخ:** 2026-06-15 | **الحالة:** مقبول

## القرار
- **Web routes**: Laravel Session auth (stateful)
- **API routes**: Sanctum token auth (stateless)

## الأسباب
- Web: UX أفضل مع sessions وCSRF protection
- API: مناسب للـ mobile apps وexternal integrations مستقبلاً
- Sanctum يدعم كلا النمطين

---

# ADR-005: Soft Deletes على كل البيانات الحساسة
**التاريخ:** 2026-06-20 | **الحالة:** مقبول

## القرار
كل جداول تحتوي بيانات أكاديمية أو شخصية تستخدم Soft Deletes.

## الأسباب
- Compliance مع لوائح حماية البيانات
- Audit trail كامل
- إمكانية استرجاع البيانات المحذوفة
- لا فقدان غير مقصود للبيانات
