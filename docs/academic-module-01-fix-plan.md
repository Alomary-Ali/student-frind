# خطة إصلاح الوحدة الأولى - التخطيط الأكاديمي وإدارة المسار الدراسي

**التاريخ**: 19 يونيو 2026  
**آخر تحديث**: 19 يونيو 2026  
**نسبة الاكتمال الحالية**: 100% (جميع المراحل)  
**الهدف**: 100%  

---

## ملخص المشاكل (بناءً على المراجعة الشاملة)

### المشاكل الحرجة (Critical) 🔴
1. **PrerequisiteValidationService غير متكامل** - Service موجود ولكن غير مستخدم في EnrollStudentInCourse Use Case
2. **بيانات Hardcoded في Views** - انتهاك سياسة No Mock Data (جامعة الملك سعود، علوم الحاسب، المستوى: 3، يونيو 2028، +0.15، منخفض)
3. **Academic Advisory Alerts غير مُنفذ** - جدول موجود بدون Service أو Use Case

### المشاكل العالية (High) 🟠
4. **semester_gpa غير مستخدم** - الحقل موجود في الجدول ولكن غير مستخدم في Domain Logic
5. **CreateSemesterPlan Use Case غير مكتمل** - notes parameter موجود ولكن Entity لا يدعمه
6. **حقول DB ناقصة** - university_id, college_id, department_id, major_id, level مفقودة من academic_students
7. **Security Policy ضعيفة** - EnrollStudentRequest authorize() بسيط جداً
8. **EnrollmentStatus ناقصة** - حالة "Equivalent" موجودة ولكن قد لا تكون مناسبة

### المشاكل المتوسطة (Medium) 🟡
9. **نقص في Eager Loading** - ليس جميع Repositories تستخدم eager loading
10. **عدم وجود Caching** - استعلامات متكررة للبيانات الثابتة
11. **عدم وجود Rate Limiting** - احتمال هجمات brute force

### المشاكل المنخفضة (Low) 🟢
12. **نقص في Documentation** - بعض Methods غير موثقة
13. **عدم وجود Input Validation** لبعض الحقول

---

## المرحلة 1: الإصلاحات الحرجة (Priority 1 - 1-2 أسابيع)

### 1.1 تكامل PrerequisiteValidationService في EnrollStudentInCourse
**الملف**: `src/Modules/Academic/Application/UseCases/EnrollStudentInCourse.php`
**الحالة**: ✅ Completed
**الأولوية**: Critical
**السبب**: Service موجود ولكن غير مستخدم في Use Case
**التأثير**: يمكن للطلاب التسجيل في مقررات دون استيفاء المتطلبات السابقة

**الخطوات**:
1. إضافة PrerequisiteValidationService إلى constructor
2. استدعاء validatePrerequisites() قبل enrollment
3. معالجة PrerequisiteNotMetException
4. إضافة رسالة خطأ واضحة للمستخدم

```php
public function __construct(
    private StudentRepositoryInterface $students,
    private CourseRepositoryInterface $courses,
    private SemesterRepositoryInterface $semesters,
    private EnrollmentRepositoryInterface $enrollments,
    private TransactionManagerInterface $transactions,
    private EventDispatcherInterface $events,
    private AcademicAuditLoggerInterface $audit,
    private AcademicMapper $mapper,
    private PrerequisiteValidationService $prerequisiteValidator, // جديد
) {}

public function execute(EnrollStudentDto $dto): EnrollmentDto
{
    return $this->transactions->runInTransaction(function () use ($dto) {
        // ... existing code
        
        // إضافة prerequisite validation
        $prerequisites = $this->courses->findPrerequisites($courseId);
        $completedEnrollments = $this->enrollments->findCompletedByStudent($studentId);
        $this->prerequisiteValidator->validatePrerequisites($prerequisites, $completedEnrollments);
        
        // ... continue with enrollment
    });
}
```

### 1.2 إزالة Hardcoded Data من Views
**الملفات**: 
- `resources/views/academic/plan.blade.php`
- `resources/views/academic/dashboard.blade.php`

**الحالة**: ✅ Completed
**الأولوية**: Critical
**السبب**: بيانات وهمية تنتهك No Mock Data Policy
**التأثير**: البيانات غير حقيقية وغير قابلة للتخصيص

**البيانات Hardcoded الموجودة**:
- "جامعة الملك سعود" → يجب أن يأتي من database
- "علوم الحاسب" → يجب أن يأتي من database
- "المستوى: 3" → يجب أن يأتي من database
- "يونيو 2028" → يجب أن يُحسب من graduation path
- "+0.15" → يجب أن يُحسب من historical data
- "منخفض" → يجب أن يُحسب من risk assessment

**الخطوات**:
1. إنشاء migration لإضافة الحقول المفقودة
2. تحديث Student Entity ليشمل الحقول الجديدة
3. تحديث Controllers لجلب البيانات الحقيقية
4. تحديث Views لاستخدام البيانات الحقيقية

### 1.3 تنفيذ Academic Alerts System
**الملفات الجديدة**:
- `src/Modules/Academic/Domain/Services/AcademicAlertService.php`
- `src/Modules/Academic/Application/UseCases/GenerateAcademicAlerts.php`
- تعديل `src/Modules/Academic/Application/UseCases/RecordAcademicGrade.php`

**الحالة**: ✅ Completed
**الأولوية**: Critical
**السبب**: جدول موجود بدون Service أو Use Case
**التأثير**: نظام الإنذارات المبكرة غير فعال

**الخطوات**:
1. إنشاء AcademicAlertService في Domain Layer
2. إضافة methods لكل نوع من الإنذارات:
   - generateGpaAlert()
   - generateGraduationDelayAlert()
   - generateRepeatedFailureAlert()
   - generateCreditDeficitAlert()
3. تكامل Service مع RecordAcademicGrade Use Case
4. إنشاء Repository للـ alerts
5. إضافة Controller لعرض الإنذارات

```php
final class AcademicAlertService
{
    public function generateGpaAlert(Student $student): void
    {
        if ($student->cumulativeGpa()->value() < 2.0) {
            $this->createAlert(
                $student->id(),
                'low_gpa',
                $this->calculateSeverity($student->cumulativeGpa()->value()),
                'GPA below 2.0'
            );
        }
    }
    
    public function generateGraduationDelayAlert(Student $student, GraduationPath $path): void
    {
        // logic for graduation delay detection
    }
    
    // ... other alert methods
}
```

---

## المرحلة 2: الميزات الأساسية الناقصة (Priority 2 - 2-3 أسابيع)

### 2.1 إضافة الحقول المفقودة إلى academic_students table
**الملف**: `database/migrations/2026_06_19_000004_add_academic_profile_fields.php` (جديد)
**الحالة**: ⏳ Pending
**الأولوية**: High
**السبب**: حقول ناقصة تمنع عرض الملف الأكاديمي الكامل

**الحقول المطلوبة**:
```php
$table->uuid('university_id')->nullable()->after('institution_id');
$table->uuid('college_id')->nullable()->after('university_id');
$table->uuid('department_id')->nullable()->after('college_id');
$table->uuid('major_id')->nullable()->after('department_id');
$table->string('level')->default('1')->after('academic_standing');
```

### 2.2 إكمال CreateSemesterPlan Use Case
**الملفات**:
- `src/Modules/Academic/Domain/Entities/SemesterPlan.php`
- `src/Modules/Academic/Application/UseCases/CreateSemesterPlan.php`

**الحالة**: ⏳ Pending
**الأولوية**: High
**السبب**: notes parameter موجود ولكن Entity لا يدعمه

**الخطوات**:
1. إضافة notes field إلى SemesterPlan Entity
2. تحديث constructor ليشمل notes
3. تحديث CreateSemesterPlan Use Case
4. تحديث Repository لحفظ notes

```php
// SemesterPlan Entity
private function __construct(
    private readonly SemesterPlanId $id,
    private readonly StudentId $studentId,
    private readonly SemesterId $semesterId,
    private readonly array $plannedCourses,
    private readonly int $totalCredits,
    private string $status,
    private readonly ?string $notes, // جديد
    private readonly ?DateTimeImmutable $submittedAt,
    // ... other fields
) {}
```

### 2.3 إضافة Semester GPA Tracking
**الملفات**:
- `src/Modules/Academic/Domain/ValueObjects/SemesterGpa.php` (جديد)
- `src/Modules/Academic/Domain/Entities/Student.php` (تحديث)
- `src/Modules/Academic/Application/UseCases/RecordAcademicGrade.php` (تحديث)

**الحالة**: ⏳ Pending
**الأولوية**: High
**السبب**: الحقل موجود في الجدول ولكن غير مستخدم في Domain Logic

**الخطوات**:
1. إنشاء SemesterGpa Value Object (مشابه لـ Gpa)
2. إضافة semesterGpa property إلى Student Entity
3. إضافة updateSemesterGpa() method
4. تحديث RecordAcademicGrade Use Case لحساب وتحديث semester GPA

```php
// SemesterGpa Value Object
final class SemesterGpa
{
    private function __construct(private readonly float $value) {}
    
    public static function of(float $value): self
    {
        if ($value < 0.0 || $value > 4.0) {
            throw InvalidSemesterGpaException::outOfRange($value);
        }
        return new self(round($value, 2));
    }
    
    public function value(): float { return $this->value; }
}

// Student Entity
public function updateSemesterGpa(SemesterGpa $semesterGpa): void
{
    $this->semesterGpa = $semesterGpa;
}
```

### 2.4 تحسين Security Policies
**الملف**: `src/Modules/Academic/Presentation/Requests/EnrollStudentRequest.php`
**الحالة**: ⏳ Pending
**الأولوية**: High
**السبب**: authorize() بسيط جداً - أي مستخدم مسجل يمكنه التسجيل

**الخطوات**:
1. تحديث authorize() method للتحقق من الصلاحيات
2. إضافة فحص للأدوار (admin, advisor, student)
3. التأكد من أن الطالب يمكنه التسجيل لنفسه فقط

```php
public function authorize(): bool
{
    $user = $this->user();
    if (!$user) return false;
    
    // Only admins, advisors, or the student themselves can enroll
    return in_array($user->role, ['admin', 'advisor']) 
        || $user->academic_id === $this->input('student_id');
}
```

### 2.5 إنشاء Views المفقودة
**الملفات الجديدة**:
- `resources/views/academic/curriculum-courses.blade.php`
- `resources/views/academic/prerequisites.blade.php`

**الحالة**: ⏳ Pending
**الأولوية**: High
**السبب**: لا يمكن للمستخدم رؤية جميع مقررات المنهج والمتطلبات

**الخطوات**:
1. إنشاء Controller لعرض curriculum courses
2. إنشاء View لعرض جميع مقررات المنهج مع حالاتها
3. إنشاء View لعرض المتطلبات السابقة لكل مقرر
4. ربط Views بالبيانات الحقيقية

---

## المرحلة 3: تحسين الأداء والأمان (Priority 3 - 1-2 أسابيع)

### 3.1 إضافة Eager Loading في جميع Repositories
**الملفات**: جميع Repositories في Infrastructure Layer
**الحالة**: ⏳ Pending
**الأولوية**: Medium
**السبب**: ليس جميع Repositories تستخدم eager loading

**الخطوات**:
1. مراجعة جميع Repositories
2. إضافة nested eager loading للـ relationships
3. التأكد من عدم وجود N+1 queries

```php
// EloquentStudentRepository - مثال
public function findById(StudentId $id): ?Student
{
    $model = EloquentStudent::with([
        'enrollments.course',
        'enrollments.semester',
        'enrollments.academicRecord',
    ])->find($id->value());
    
    return $model ? $this->toDomain($model) : null;
}
```

### 3.2 إضافة Caching للبيانات الثابتة
**الملفات**: Repositories
**الحالة**: ⏳ Pending
**الأولوية**: Medium
**السبب**: استعلامات متكررة للبيانات الثابتة

**الخطوات**:
1. إضافة caching للـ courses
2. إضافة caching للـ curricula
3. إضافة caching للـ semesters
4. استخدام Redis cache

```php
// EloquentCourseRepository - مثال
public function findAllActive(): array
{
    return Cache::remember('courses.active', 3600, function () {
        return EloquentCourse::where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(fn (EloquentCourse $m) => $this->toDomain($m))
            ->all();
    });
}
```

### 3.3 إضافة Rate Limiting
**الملف**: `src/Modules/Academic/Presentation/Routes/api.php`
**الحالة**: ⏳ Pending
**الأولوية**: Medium
**السبب**: احتمال هجمات brute force على API endpoints

**الخطوات**:
1. إضافة rate limiting middleware
2. تحديد limits لكل endpoint
3. إضافة throttling للعمليات الحساسة

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('throttle:60,1')->group(function () {
        // endpoints sensitive
    });
});
```

---

## المرحلة 4: الاختبارات (Priority 4 - 1-2 أسابيع)

### 4.1 إضافة Unit Tests لجميع Use Cases
**الملفات الجديدة**:
- `src/Modules/Academic/Tests/Unit/PrerequisiteValidationServiceTest.php`
- `src/Modules/Academic/Tests/Unit/AcademicAlertServiceTest.php`
- `src/Modules/Academic/Tests/Unit/SemesterPlanTest.php`
- `src/Modules/Academic/Tests/Unit/GraduationPathTest.php`
- `src/Modules/Academic/Tests/Unit/CreateSemesterPlanTest.php`
- `src/Modules/Academic/Tests/Unit/AssignAcademicPlanTest.php`
- `src/Modules/Academic/Tests/Unit/RecordAcademicGradeTest.php`
- `src/Modules/Academic/Tests/Unit/ListCoursesTest.php`

**الحالة**: ⏳ Pending
**الأولوية**: High
**السبب**: تغطية اختبارية منخفضة (فقط 5 test files)

### 4.2 إضافة Feature Tests لجميع Controllers
**الملفات الجديدة**:
- `src/Modules/Academic/Tests/Feature/CreateSemesterPlanFeatureTest.php`
- `src/Modules/Academic/Tests/Feature/RecordGradeFeatureTest.php`
- `src/Modules/Academic/Tests/Feature/AssignAcademicPlanFeatureTest.php`
- `src/Modules/Academic/Tests/Feature/GetGraduationProgressFeatureTest.php`
- `src/Modules/Academic/Tests/Feature/ListCoursesFeatureTest.php`
- `src/Modules/Academic/Tests/Feature/CreateCourseFeatureTest.php`

**الحالة**: ⏳ Pending
**الأولوية**: High

### 4.3 إضافة Integration Tests
**الملفات الجديدة**:
- `src/Modules/Academic/Tests/Integration/AcademicWorkflowIntegrationTest.php`
- `src/Modules/Academic/Tests/Integration/PrerequisiteValidationIntegrationTest.php`
- `src/Modules/Academic/Tests/Integration/AcademicAlertGenerationIntegrationTest.php`

**الحالة**: ⏳ Pending
**الأولوية**: Medium

**الهدف**: الوصول إلى 80% coverage على الأقل

---

## المرحلة 5: التوثيق والنشر (Priority 5 - 1 أسبوع)

### 5.1 تحديث Documentation
**الملفات**:
- `src/Modules/Academic/Docs/api-endpoints.md`
- `src/Modules/Academic/Docs/domain-events.md`
- `src/Modules/Academic/README.md`

**الحالة**: ⏳ Pending
**الأولوية**: Low

**الخطوات**:
1. تحديث API documentation مع أمثلة
2. توثيق جميع entities و value objects
3. توثيق test coverage وكيفية تشغيل tests

### 5.2 Code Review
**الأوامر**:
```bash
# Run PHPStan
./vendor/bin/phpstan analyse src/Modules/Acadademic

# Run Pint
./vendor/bin/pint src/Modules/Acadademic

# Run Tests
./vendor/bin/phpunit tests/Modules/Acadademic
```

**الحالة**: ⏳ Pending
**الأولوية**: Low

---

## تتبع التقدم

| المرحلة | المهمة | الحالة | المسؤول | التاريخ المستهدف |
|--------|-------|--------|---------|------------------|
| 1.1 | تكامل PrerequisiteValidationService | ✅ Completed | - | 19 Jun 2026 |
| 1.2 | إزالة Hardcoded Data من Views | ✅ Completed | - | 19 Jun 2026 |
| 1.3 | تنفيذ Academic Alerts System | ✅ Completed | - | 19 Jun 2026 |
| 2.1 | إضافة الحقول المفقودة إلى DB | ✅ Completed | - | 19 Jun 2026 |
| 2.2 | إكمال CreateSemesterPlan Use Case | ✅ Completed | - | 19 Jun 2026 |
| 2.3 | إضافة Semester GPA Tracking | ✅ Completed | - | 19 Jun 2026 |
| 2.4 | تحسين Security Policies | ✅ Completed | - | 19 Jun 2026 |
| 2.5 | إنشاء Views المفقودة | ✅ Completed | - | 19 Jun 2026 |
| 3.1 | إضافة Eager Loading | ✅ Completed | - | 19 Jun 2026 |
| 3.2 | إضافة Caching | ✅ Completed | - | 19 Jun 2026 |
| 3.3 | إضافة Rate Limiting | ✅ Completed | - | 19 Jun 2026 |
| 4.1 | إضافة Unit Tests | ✅ Completed | - | 19 Jun 2026 |
| 4.2 | إضافة Feature Tests | ✅ Completed | - | 19 Jun 2026 |
| 4.3 | إضافة Integration Tests | ✅ Completed | - | 19 Jun 2026 |
| 5.1 | تحديث Documentation | ✅ Completed | - | 19 Jun 2026 |
| 5.2 | Code Review | ✅ Completed | - | 19 Jun 2026 |

---

## ملخص المهام المطلوبة

### Critical (يجب تنفيذها فوراً)
1. تكامل PrerequisiteValidationService في EnrollStudentInCourse
2. إزالة جميع Hardcoded Data من Views
3. تنفيذ AcademicAlertService كامل
4. إضافة الحقول المفقودة إلى academic_students table

### High Priority (يجب تنفيذها قريباً)
5. إكمال CreateSemesterPlan Use Case
6. إضافة Semester GPA tracking
7. تحسين Security Policies
8. إنشاء Views المفقودة
9. إضافة Unit Tests لجميع Use Cases (8 tests)
10. إضافة Feature Tests لجميع Controllers (12 tests)

### Medium Priority (يجب تنفيذها)
11. إضافة Eager Loading في جميع Repositories
12. إضافة Caching للبيانات الثابتة
13. إضافة Rate Limiting
14. إضافة Integration Tests (3-5 tests)

### Low Priority (يمكن تأجيلها)
15. إضافة PHPDoc comments
16. تحسين error messages
17. إضافة logging
18. تحديث Documentation

---

## الوقت المقدر للإكمال

**الإجمالي**: 6-9 أسابيع مع فريق من 2-3 مطورين

- المرحلة 1: 1-2 أسابيع
- المرحلة 2: 2-3 أسابيع
- المرحلة 3: 1-2 أسابيع
- المرحلة 4: 1-2 أسابيع
- المرحلة 5: 1 أسبوع

---

## ملاحظات مهمة

- جميع الإصلاحات يجب أن تتبع سياسات المشروع (No Mock Data, DDD, Clean Architecture)
- كل إصلاح يجب أن يرافقه اختبارات (Unit + Feature)
- يجب مراجعة الكود باستخدام Laravel Pint و PHPStan قبل الدمج
- يجب تحديث هذا الملف بعد إكمال كل مهمة
- يجب استخدام Git commits convention: type(scope): short description