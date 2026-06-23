# خطة إصلاح مخالفات ENGINEERING_RULEBOOK

## PHASE 1 — حرجة (6 مخالفات)

### 1.1 Productivity API Routes — إضافة Auth + Rate Limiting
**الملف:** src/Modules/Productivity/Presentation/Http/routes.php
**الإصلاح:**
- إضافة Route::middleware('auth:sanctum') حول الـ group بالكامل
- إضافة ->middleware('throttle:60,1') لكل GET route
- إضافة ->middleware('throttle:30,1') لكل POST/PATCH route

### 1.2 POST /login خارج guest group
**الملف:** outes/web.php (السطر 35-37)
**الإصلاح:** نقل Route::post('/login', ...) داخل Route::middleware('guest')->group(...) في السطر 27

### 1.3 API register/login بدون guest middleware
**الملف:** src/Modules/Shared/Presentation/Routes/api.php (السطر 10-17)
**الإصلاح:** إضافة Route::middleware('guest')->group(function () { ... }) حول register و login

### 1.4 Comment-Code mismatch في Academic API
**الملف:** src/Modules/Academic/Presentation/Routes/api.php (السطر 29-31)
**الإصلاح:** تغيير التعليق من "Public course list (no authentication required)" إلى "Course list (requires authentication)"

### 1.5 Hardcoded data في academic/plan.blade.php
**الملف:** esources/views/academic/plan.blade.php (السطور 117-205, 224-300, 308, 331)
**الإصلاح:** استبدال البيانات المثبتة بـ @foreach على بيانات حقيقية من الـ Controller

---

## PHASE 2 — عالية (5 مخالفات)

### 2.1 LogoutController — Direct DB
**الملف:** src/Modules/Shared/Presentation/Controllers/LogoutController.php:35
**الإصلاح:** استبدال DB::table('sessions') بـ session()->getHandler()->destroy()

### 2.2 Routes بدون Middleware
**الملفات:** outes/web.php السطر 39 (/unauthorized) و 116 (/)
**الإصلاح:** إضافة ->middleware('guest') لكليهما

### 2.3 Closure Routes ← Controllers (6 routes)
**الملف:** outes/web.php السطور 28, 29, 30, 31, 39, 60
**الإصلاح:** إنشاء AuthPageController و AcademicProgressController

### 2.4 God Controllers (>3 deps)
**الإصلاح:** دمج Use Cases أو إنشاء ViewModel لتقليص الـ dependencies

---

## PHASE 3 — متوسطة (9 مخالفات)

### 3.1 ألوان غير معتمدة (8+ ملفات Blade)
### 3.2 Inline Styles (10 حالات)
### 3.3-3.7 Migrations (FK + indexes + nullable + enum + softDeletes)
### 3.8 Relationship methods (19 موديل)
### 3.9 Business Logic في Views ← Accessors/Helpers

---

## PHASE 4 — منخفضة (4 مخالفات)

### 4.1 nav-link-danger ← app.css
### 4.2 Missing \/\
### 4.3 course_id string → uuid
### 4.4 \App\Models\User → EloquentUser
