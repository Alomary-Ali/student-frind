# تنفيذ نظام التفويض (Authorization System)
## المرحلة 1 من خطة الإصلاحات الشاملة المحدثة

**التاريخ:** 19 يونيو 2026  
**الحالة:** ✅ مكتمل  
**المدة:** 3 أيام (مُقدرة 0.5 أسبوع للمرحلة 1.1)

---

# نظرة عامة

تم تنفيذ نظام كامل للتفويض (Authorization System) باستخدام RBAC (Role-Based Access Control) مع دعم الصلاحيات الدقيقة. النظام يتبع معايير المشروع الهندسية (DDD, Clean Architecture, Strict Typing).

---

# المكونات المنفذة

## 1. Domain Layer

### Enums
- **Role.php**: Enum للأدوار (super_admin, admin, advisor, student, faculty)
  - methods: `label()`, `canManageSystem()`, `canManageUniversity()`, `canAdviseStudents()`, `canManageCourses()`

### Value Objects
- **Permission.php**: Value Object للصلاحيات
  - factory methods: `studentsView()`, `studentsCreate()`, `studentsUpdate()`, `studentsDelete()`, `enrollmentsView()`, `enrollmentsCreate()`, `enrollmentsDelete()`, `gradesView()`, `gradesCreate()`, `gradesUpdate()`, `reportsView()`, `settingsManage()`
- **RoleId.php**: Value Object لمعرف الدور (UUID)
- **PermissionId.php**: Value Object لمعرف الصلاحية (UUID)

### Entities
- **Role.php**: Entity للدور
  - methods: `id()`, `name()`, `permissions()`, `hasPermission()`, `addPermission()`, `removePermission()`
- **Permission.php**: Entity للصلاحية
  - methods: `id()`, `name()`, `description()`

### Contracts
- **RoleRepositoryInterface.php**: Repository Interface للأدوار
  - methods: `findById()`, `findByName()`, `findAll()`, `save()`, `delete()`, `findByUserIds()`
- **PermissionRepositoryInterface.php**: Repository Interface للصلاحيات
  - methods: `findById()`, `findByName()`, `findAll()`, `save()`, `delete()`, `findByRoleId()`

---

## 2. Infrastructure Layer

### Persistence
- **EloquentRole.php**: Eloquent Model للدور
  - relationships: `permissions()`, `users()`
- **EloquentPermission.php**: Eloquent Model للصلاحية
  - relationships: `roles()`

### Repositories
- **EloquentRoleRepository.php**: Repository Implementation للأدوار
  - methods: `findById()`, `findByName()`, `findAll()`, `save()`, `delete()`, `findByUserIds()`
  - features: eager loading permissions, auto-create permissions
- **EloquentPermissionRepository.php**: Repository Implementation للصلاحيات
  - methods: `findById()`, `findByName()`, `findAll()`, `save()`, `delete()`, `findByRoleId()`

### Middleware
- **AuthorizationMiddleware.php**: Middleware للتفويض الأساسي
  - checks: authentication status
  - returns: 401 if not authenticated
- **RoleMiddleware.php**: Middleware للأدوار
  - checks: user has required role
  - returns: 403 if not authorized
- **PermissionMiddleware.php**: Middleware للصلاحيات
  - checks: user has required permission
  - returns: 403 if not authorized

---

## 3. Presentation Layer

### Policies
- **Policy.php**: Base Policy class
  - helper methods: `hasPermission()`, `hasRole()`, `isSuperAdmin()`, `isAdmin()`, `isAdvisor()`, `isStudent()`, `isFaculty()`
  - response methods: `deny()`, `allow()`
- **StudentPolicy.php**: Policy للطلاب
  - methods: `view()`, `update()`, `delete()`, `create()`
- **EnrollmentPolicy.php**: Policy للتسجيلات
  - methods: `view()`, `create()`, `delete()`
- **CoursePolicy.php**: Policy للمقررات
  - methods: `view()`, `create()`, `update()`, `delete()`

---

## 4. Database Schema

### Tables
- **roles**: جدول الأدوار
  - columns: id (UUID), name (string, unique), label (string), timestamps
  - indexes: name
- **permissions**: جدول الصلاحيات
  - columns: id (UUID), name (string, unique), description (text, nullable), timestamps
  - indexes: name
- **role_permissions**: جدول ربط الأدوار بالصلاحيات
  - columns: role_id (UUID), permission_id (UUID), timestamps
  - foreign keys: role_id → roles.id (cascade), permission_id → permissions.id (cascade)
  - primary key: (role_id, permission_id)
- **user_roles**: جدول ربط المستخدمين بالأدوار
  - columns: user_id (UUID), role_id (UUID), timestamps
  - foreign keys: user_id → users.id (cascade), role_id → roles.id (cascade)
  - primary key: (user_id, role_id)

---

# الأدوار والصلاحيات

## الأدوار (Roles)
1. **super_admin**: مدير النظام
   - الصلاحيات: جميع الصلاحيات
2. **admin**: مدير الجامعة
   - الصلاحيات: إدارة الجامعة، الطلاب، التسجيلات، الدرجات، التقارير
3. **advisor**: المرشد الأكاديمي
   - الصلاحيات: عرض الطلاب، إنشاء الطلاب، التسجيلات، الدرجات
4. **student**: الطالب
   - الصلاحيات: عرض بياناته الخاصة، التسجيل
5. **faculty**: عضو هيئة التدريس
   - الصلاحيات: عرض المقررات، إنشاء المقررات، تعديل المقررات

## الصلاحيات (Permissions)
- **students.view**: عرض بيانات الطلاب
- **students.create**: إنشاء طالب
- **students.update**: تعديل بيانات الطالب
- **students.delete**: حذف طالب
- **enrollments.view**: عرض التسجيلات
- **enrollments.create**: إنشاء تسجيل
- **enrollments.delete**: حذف تسجيل
- **grades.view**: عرض الدرجات
- **grades.create**: إضافة درجات
- **grades.update**: تعديل درجات
- **reports.view**: عرض التقارير
- **settings.manage**: إدارة الإعدادات

---

# معايير الجودة

## الالتزام بقواعد المشروع
- ✅ `declare(strict_types=1)` في جميع الملفات
- ✅ `final class` لجميع الـ Entities و Value Objects
- ✅ `readonly properties` على جميع الـ DTOs و Value Objects
- ✅ Full type hints (parameters + return types)
- ✅ Constructor injection only
- ✅ Naming conventions (PascalCase للـ classes, camelCase للـ methods)
- ✅ Layer rules (Domain: Pure PHP, Infrastructure: Eloquent, Presentation: Controllers/Policies)

## Code Quality
- ✅ No magic methods
- ✅ No global state
- ✅ Proper separation of concerns
- ✅ Interface-based design
- ✅ Repository pattern
- ✅ Value objects for domain concepts

---

# الخطوات التالية

## المطلوب
1. تسجيل الـ middleware في `app/Http/Kernel.php`
2. تسجيل الـ repositories في `src/Modules/Shared/SharedServiceProvider.php`
3. تشغيل الـ migrations
4. إنشاء seeder للأدوار والصلاحيات الافتراضية
5. تحديث `users` table لإضافة `role` column (أو استخدام user_roles table)
6. تطبيق الـ middleware على الـ routes
7. كتابة Unit Tests للـ Repositories
8. كتابة Feature Tests للـ Middleware
9. كتابة Feature Tests للـ Policies

## المرحلة التالية
المرحلة 1.2: حماية المسارات (Route Protection)
- تطبيق middleware على جميع الـ routes
- حماية الـ admin routes
- حماية الـ advisor routes
- حماية الـ student routes

---

# الملفات المنشأة

## Domain Layer
```
src/Modules/Shared/Domain/
├── Enums/
│   └── Role.php
├── ValueObjects/
│   ├── Permission.php
│   ├── RoleId.php
│   └── PermissionId.php
├── Entities/
│   ├── Role.php
│   └── Permission.php
└── Contracts/
    ├── RoleRepositoryInterface.php
    └── PermissionRepositoryInterface.php
```

## Infrastructure Layer
```
src/Modules/Shared/Infrastructure/
├── Persistence/
│   ├── EloquentRole.php
│   └── EloquentPermission.php
├── Repositories/
│   ├── EloquentRoleRepository.php
│   └── EloquentPermissionRepository.php
└── Middleware/
    ├── AuthorizationMiddleware.php
    ├── RoleMiddleware.php
    └── PermissionMiddleware.php
```

## Presentation Layer
```
src/Modules/Shared/Presentation/
└── Policies/
    └── Policy.php

src/Modules/Academic/Presentation/
└── Policies/
    ├── StudentPolicy.php (مُعدل)
    ├── EnrollmentPolicy.php (جديد)
    └── CoursePolicy.php (جديد)
```

## Database
```
database/migrations/
├── 2026_06_19_000006_create_roles_table.php
├── 2026_06_19_000007_create_permissions_table.php
├── 2026_06_19_000008_create_role_permissions_table.php
└── 2026_06_19_000009_create_user_roles_table.php
```

---

# الخلاصة

تم بنجاح تنفيذ المرحلة الأولى من نظام التفويض (Authorization System) مع الالتزام الكامل بمعايير المشروع الهندسية. النظام جاهز للاستخدام بعد تسجيل الـ middleware والـ repositories في service providers.

**الحالة:** ✅ Phase 1 Completed  
**التالي:** Phase 1.2 - Route Protection
