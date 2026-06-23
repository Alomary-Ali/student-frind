# خطة البيانات الأولية للنظام (Seed Data Plan)

**التاريخ:** 19 يونيو 2026
**الهدف:** إنشاء بيانات أولية شاملة لاختبار وتجربة النظام

---

## المبادئ الأساسية

### 1. الالتزام بقواعد النظام
- ✅ استخدام Factories للبيانات (لا mock data)
- ✅ البيانات واقعية للبيئة الأكاديمية السعودية
- ✅ استخدام Value Objects الصحيحة (AcademicId, EmailAddress, etc.)
- ✅ اتباع الـ DDD Layer Rules
- ✅ استخدام Domain Events عند الحاجة
- ✅ strict_types, final classes, full type hints

### 2. البيانات المطلوبة
- Users مع أدوار مختلفة (SUPER_ADMIN, ADMIN, ADVISOR, STUDENT, FACULTY)
- Students مع بيانات أكاديمية كاملة
- Courses (مقررات دراسية)
- Semesters (فصول دراسية)
- Enrollments (تسجيلات)
- Grades (درجات)
- Academic Plans (خطط دراسية)
- Universities/Colleges/Departments (للبيئة متعددة الجامعات اليمنية)

---

## المرحلة 1: إعداد البنية الأساسية (Infrastructure)

### 1.1 إنشاء Factories
**الملفات المطلوبة:**
- `database/factories/UserFactory.php` - تحديث ليشمل جميع الأدوار
- `database/factories/StudentFactory.php` - جديد
- `database/factories/CourseFactory.php` - جديد
- `database/factories/SemesterFactory.php` - جديد
- `database/factories/EnrollmentFactory.php` - جديد
- `database/factories/GradeFactory.php` - جديد
- `database/factories/UniversityFactory.php` - جديد
- `database/factories/CollegeFactory.php` - جديد
- `database/factories/DepartmentFactory.php` - جديد
- `database/factories/MajorFactory.php` - جديد

### 1.2 تحديث UserFactory
```php
// يجب أن يتضمن:
- academic_id (رقم أكاديمي واقعي)
- email (بريد إلكتروني صحيح)
- first_name, last_name (أسماء عربية واقعية)
- password_hash (كلمة مرور مشفرة)
- role (من Role enum)
- status (من UserStatus enum)
- failed_login_attempts, locked_until (للأمان)
```

### 1.3 إنشاء StudentFactory
```php
// يجب أن يتضمن:
- university_id, college_id, department_id, major_id
- level (1-5)
- gpa (0.0-4.0)
- academic_standing (من AcademicStanding enum)
- enrollment_status (من EnrollmentStatus enum)
```

---

## المرحلة 2: البيانات الأساسية (Reference Data)

### 2.1 إنشاء UniversitySeeder
**الهدف:** إنشاء جامعات يمنية واقعية
```php
الجامعات:
1. جامعة صنعاء
2. جامعة عدن
3. جامعة ذمار
4. جامعة الحديدة
5. جامعة إب
```

### 2.2 إنشاء CollegeSeeder
**الهدف:** إنشاء كليات لكل جامعة
```php
الكليات (لكل جامعة):
- كلية علوم الحاسب والمعلومات
- كلية الهندسة
- كلية الطب
- كلية العلوم
- كلية الإدارة
```

### 2.3 إنشاء DepartmentSeeder
**الهدف:** إنشاء أقسام لكل كلية
```php
الأقسام (لكل كلية):
- قسم علوم الحاسب
- قسم هندسة البرمجيات
- قسم الشبكات
- قسم الأمن السيبراني
- قسم الذكاء الاصطناعي
```

### 2.4 إنشاء MajorSeeder
**الهدف:** إنشاء تخصصات
```php
التخصصات:
- علوم الحاسب
- هندسة البرمجيات
- الأمن السيبراني
- الشبكات
- الذكاء الاصطناعي
- علوم البيانات
```

### 2.5 إنشاء SemesterSeeder
**الهدف:** إنشاء فصول دراسية
```php
الفصول الدراسية:
- الفصل الأول 2024-2025
- الفصل الثاني 2024-2025
- الفصل الصيفي 2024-2025
- الفصل الأول 2025-2026
- الفصل الثاني 2025-2026
```

---

## المرحلة 3: بيانات المستخدمين (Users)

### 3.1 إنشاء TestUsersSeeder
**الهدف:** إنشاء مستخدمين اختباريين بجميع الأدوار

**المستخدمون المطلوبون:**

#### SUPER_ADMIN (1 مستخدم)
```php
- academic_id: 1000000001
- email: super.admin@sanaa-univ.edu.ye
- first_name: أحمد
- last_name: الصنعاني
- password: Admin@1234
- role: SUPER_ADMIN
- status: Active
```

#### ADMIN (2 مستخدمين)
```php
1. academic_id: 2000000001
   email: admin.sanaa@sanaa-univ.edu.ye
   first_name: سارة
   last_name: الحدادي
   password: Admin@1234
   role: ADMIN
   status: Active

2. academic_id: 2000000002
   email: admin.aden@aden-univ.edu.ye
   first_name: محمد
   last_name: العدني
   password: Admin@1234
   role: ADMIN
   status: Active
```

#### ADVISOR (3 مستخدمين)
```php
1. academic_id: 3000000001
   email: advisor1@sanaa-univ.edu.ye
   first_name: فاطمة
   last_name: الإبائي
   password: Advisor@1234
   role: ADVISOR
   status: Active

2. academic_id: 3000000002
   email: advisor2@ibb-univ.edu.ye
   first_name: خالد
   last_name: الذماري
   password: Advisor@1234
   role: ADVISOR
   status: Active

3. academic_id: 3000000003
   email: advisor3@hodeidah-univ.edu.ye
   first_name: نورة
   last_name: الحدادي
   password: Advisor@1234
   role: ADVISOR
   status: Active
```

#### FACULTY (3 مستخدمين)
```php
1. academic_id: 4000000001
   email: faculty1@sanaa-univ.edu.ye
   first_name: د. عبدالرحمن
   last_name: الصنعاني
   password: Faculty@1234
   role: FACULTY
   status: Active

2. academic_id: 4000000002
   email: faculty2@aden-univ.edu.ye
   first_name: د. منى
   last_name: العدنية
   password: Faculty@1234
   role: FACULTY
   status: Active

3. academic_id: 4000000003
   email: faculty3@dhamar-univ.edu.ye
   first_name: د. سعود
   last_name: الذماري
   password: Faculty@1234
   role: FACULTY
   status: Active
```

#### STUDENT (10 مستخدمين)
```php
1. academic_id: 5000000001
   email: student1@sanaa-univ.edu.ye
   first_name: يوسف
   last_name: الصنعاني
   password: Student@1234
   role: STUDENT
   status: Active
   level: 1
   gpa: 0.0

2. academic_id: 5000000002
   email: student2@aden-univ.edu.ye
   first_name: لينا
   last_name: العدنية
   password: Student@1234
   role: STUDENT
   status: Active
   level: 2
   gpa: 3.5

3. academic_id: 5000000003
   email: student3@ibb-univ.edu.ye
   first_name: عمر
   last_name: الإبائي
   password: Student@1234
   role: STUDENT
   status: Active
   level: 3
   gpa: 2.8

4. academic_id: 5000000004
   email: student4@sanaa-univ.edu.ye
   first_name: ريم
   last_name: الحدادية
   password: Student@1234
   role: STUDENT
   status: Active
   level: 4
   gpa: 3.9

5. academic_id: 5000000005
   email: student5@hodeidah-univ.edu.ye
   first_name: عبدالله
   last_name: الحدادي
   password: Student@1234
   role: STUDENT
   status: Active
   level: 1
   gpa: 0.0

6. academic_id: 5000000006
   email: student6@dhamar-univ.edu.ye
   first_name: هند
   last_name: الذمارية
   password: Student@1234
   role: STUDENT
   status: Active
   level: 2
   gpa: 3.2

7. academic_id: 5000000007
   email: student7@ibb-univ.edu.ye
   first_name: فهد
   last_name: الإبائي
   password: Student@1234
   role: STUDENT
   status: Active
   level: 3
   gpa: 2.5

8. academic_id: 5000000008
   email: student8@sanaa-univ.edu.ye
   first_name: سارة
   last_name: الصنعانية
   password: Student@1234
   role: STUDENT
   status: Active
   level: 4
   gpa: 4.0

9. academic_id: 5000000009
   email: student9@aden-univ.edu.ye
   first_name: تركي
   last_name: العدني
   password: Student@1234
   role: STUDENT
   status: Active
   level: 1
   gpa: 0.0

10. academic_id: 5000000010
    email: student10@ibb-univ.edu.ye
    first_name: نورة
    last_name: الإبائية
    password: Student@1234
    role: STUDENT
    status: Active
    level: 2
    gpa: 3.7
```

---

## المرحلة 4: بيانات المقررات (Courses)

### 4.1 إنشاء CourseSeeder
**الهدف:** إنشاء مقررات دراسية واقعية

**المقررات المطلوبة:**

#### مقررات المستوى الأول (Level 1)
```php
1. CS101 - مقدمة في علوم الحاسب
   - credits: 3
   - description: مقدمة في مفاهيم علوم الحاسب الأساسية
   - prerequisites: []

2. CS102 - برمجة 1
   - credits: 4
   - description: مقدمة في البرمجة باستخدام Python
   - prerequisites: []

3. MATH101 - الرياضيات 1
   - credits: 3
   - description: حساب التفاضل والتكامل 1
   - prerequisites: []

4. ENG101 - اللغة الإنجليزية 1
   - credits: 3
   - description: مهارات اللغة الإنجليزية الأساسية
   - prerequisites: []
```

#### مقررات المستوى الثاني (Level 2)
```php
1. CS201 - هياكل البيانات
   - credits: 3
   - description: هياكل البيانات والخوارزميات
   - prerequisites: [CS102]

2. CS202 - برمجة 2
   - credits: 4
   - description: البرمجة المتقدمة باستخدام Java
   - prerequisites: [CS102]

3. MATH201 - الرياضيات 2
   - credits: 3
   - description: حساب التفاضل والتكامل 2
   - prerequisites: [MATH101]

4. CS203 - قواعد البيانات
   - credits: 3
   - description: مقدمة في قواعد البيانات
   - prerequisites: [CS102]
```

#### مقررات المستوى الثالث (Level 3)
```php
1. CS301 - أنظمة التشغيل
   - credits: 3
   - description: مبادئ أنظمة التشغيل
   - prerequisites: [CS201]

2. CS302 - الشبكات
   - credits: 3
   - description: أساسيات شبكات الحاسب
   - prerequisites: [CS201]

3. CS303 - هندسة البرمجيات
   - credits: 3
   - description: مبادئ هندسة البرمجيات
   - prerequisites: [CS202]

4. CS304 - الأمن السيبراني
   - credits: 3
   - description: مقدمة في الأمن السيبراني
   - prerequisites: [CS302]
```

#### مقررات المستوى الرابع (Level 4)
```php
1. CS401 - الذكاء الاصطناعي
   - credits: 3
   - description: مقدمة في الذكاء الاصطناعي
   - prerequisites: [CS301]

2. CS402 - تعلم الآلة
   - credits: 3
   - description: خوارزميات تعلم الآلة
   - prerequisites: [CS401]

3. CS403 - مشروع التخرج 1
   - credits: 3
   - description: مشروع تخرج جزء 1
   - prerequisites: [CS303]

4. CS404 - مشروع التخرج 2
   - credits: 3
   - description: مشروع تخرج جزء 2
   - prerequisites: [CS403]
```

---

## المرحلة 5: بيانات التسجيل (Enrollments)

### 5.1 إنشاء EnrollmentSeeder
**الهدف:** إنشاء تسجيلات للطلاب في المقررات

**التسجيلات المطلوبة:**

#### تسجيلات الطالب 1 (Level 1)
```php
- CS101 - الفصل الأول 2024-2025
- CS102 - الفصل الأول 2024-2025
- MATH101 - الفصل الأول 2024-2025
- ENG101 - الفصل الأول 2024-2025
```

#### تسجيلات الطالب 2 (Level 2)
```php
- CS201 - الفصل الأول 2024-2025
- CS202 - الفصل الأول 2024-2025
- MATH201 - الفصل الأول 2024-2025
- CS203 - الفصل الأول 2024-2025
```

#### تسجيلات الطالب 3 (Level 3)
```php
- CS301 - الفصل الأول 2024-2025
- CS302 - الفصل الأول 2024-2025
- CS303 - الفصل الأول 2024-2025
- CS304 - الفصل الأول 2024-2025
```

#### تسجيلات الطالب 4 (Level 4)
```php
- CS401 - الفصل الأول 2024-2025
- CS402 - الفصل الأول 2024-2025
- CS403 - الفصل الأول 2024-2025
```

---

## المرحلة 6: بيانات الدرجات (Grades)

### 6.1 إنشاء GradeSeeder
**الهدف:** إنشاء درجات للطلاب المسجلين

**الدرجات المطلوبة:**

#### درجات الطالب 2 (Level 2 - GPA 3.5)
```php
- CS101: A (4.0)
- CS102: A- (3.7)
- MATH101: B+ (3.3)
- ENG101: A (4.0)
```

#### درجات الطالب 3 (Level 3 - GPA 2.8)
```php
- CS101: B (3.0)
- CS102: B (3.0)
- MATH101: C+ (2.3)
- ENG101: B+ (3.3)
- CS201: B- (2.7)
- CS202: C (2.0)
- MATH201: B (3.0)
- CS203: C+ (2.3)
```

#### درجات الطالب 4 (Level 4 - GPA 3.9)
```php
- CS101: A (4.0)
- CS102: A (4.0)
- MATH101: A (4.0)
- ENG101: A (4.0)
- CS201: A (4.0)
- CS202: A (4.0)
- MATH201: A- (3.7)
- CS203: A (4.0)
- CS301: A (4.0)
- CS302: A- (3.7)
- CS303: A (4.0)
- CS304: A (4.0)
```

#### درجات الطالب 8 (Level 4 - GPA 4.0)
```php
- جميع المقررات: A (4.0)
```

---

## المرحلة 7: بيانات الخطط الدراسية (Academic Plans)

### 7.1 إنشاء AcademicPlanSeeder
**الهدف:** إنشاء خطط دراسية للطلاب

**الخطط المطلوبة:**

#### خطة الطالب 1 (Level 1)
```php
- CS101 - الفصل الأول 2024-2025
- CS102 - الفصل الأول 2024-2025
- MATH101 - الفصل الأول 2024-2025
- ENG101 - الفصل الأول 2024-2025
- CS201 - الفصل الثاني 2024-2025
- CS202 - الفصل الثاني 2024-2025
- MATH201 - الفصل الثاني 2024-2025
- CS203 - الفصل الثاني 2024-2025
```

#### خطة الطالب 2 (Level 2)
```php
- CS301 - الفصل الأول 2025-2026
- CS302 - الفصل الأول 2025-2026
- CS303 - الفصل الأول 2025-2026
- CS304 - الفصل الأول 2025-2026
- CS401 - الفصل الثاني 2025-2026
- CS402 - الفصل الثاني 2025-2026
- CS403 - الفصل الثاني 2025-2026
- CS404 - الفصل الثاني 2025-2026
```

---

## المرحلة 8: بيانات التنبيهات الأكاديمية (Academic Alerts)

### 8.1 إنشاء AcademicAlertSeeder
**الهدف:** إنشاء تنبيهات أكاديمية للطلاب

**التنبيهات المطلوبة:**

#### تنبيهات الطالب 3 (GPA 2.8 - Probation)
```php
- AlertType: low_gpa
- Severity: medium
- Message: GPA منخفض، يرجى تحسين الأداء الأكاديمي
```

#### تنبيهات الطالب 7 (GPA 2.5 - Probation)
```php
- AlertType: low_gpa
- Severity: high
- Message: GPA منخفض جداً، يرجى التواصل مع المرشد الأكاديمي
```

---

## المرحلة 9: إنشاء Master Seeder

### 9.1 إنشاء DevDataSeeder
**الهدف:** Seed رئيسي يجمع جميع الـ seeders

```php
class DevDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UniversitySeeder::class,
            CollegeSeeder::class,
            DepartmentSeeder::class,
            MajorSeeder::class,
            SemesterSeeder::class,
            TestUsersSeeder::class,
            CourseSeeder::class,
            EnrollmentSeeder::class,
            GradeSeeder::class,
            AcademicPlanSeeder::class,
            AcademicAlertSeeder::class,
        ]);
    }
}
```

---

## المرحلة 10: التحقق والاختبار

### 10.1 إنشاء SeedDataValidationTest
**الهدف:** التحقق من صحة البيانات المولدة

```php
class SeedDataValidationTest extends TestCase
{
    public function test_all_roles_have_users(): void
    public function test_students_have_academic_profiles(): void
    public function test_courses_have_prerequisites(): void
    public function test_enrollments_have_valid_semesters(): void
    public function test_grades_are_within_valid_range(): void
    public function test_academic_plans_are_complete(): void
    public function test_alerts_are_assigned_correctly(): void
}
```

---

## ترتيب التنفيذ

### الخطوة 1: إنشاء Factories
1. تحديث UserFactory
2. إنشاء StudentFactory
3. إنشاء CourseFactory
4. إنشاء SemesterFactory
5. إنشاء EnrollmentFactory
6. إنشاء GradeFactory
7. إنشاء UniversityFactory
8. إنشاء CollegeFactory
9. إنشاء DepartmentFactory
10. إنشاء MajorFactory

### الخطوة 2: إنشاء Seeders للبيانات الأساسية
1. UniversitySeeder
2. CollegeSeeder
3. DepartmentSeeder
4. MajorSeeder
5. SemesterSeeder

### الخطوة 3: إنشاء Seeders للمستخدمين
1. TestUsersSeeder

### الخطوة 4: إنشاء Seeders للمقررات
1. CourseSeeder

### الخطوة 5: إنشاء Seeders للتسجيلات والدرجات
1. EnrollmentSeeder
2. GradeSeeder

### الخطوة 6: إنشاء Seeders للخطط والتنبيهات
1. AcademicPlanSeeder
2. AcademicAlertSeeder

### الخطوة 7: إنشاء Master Seeder
1. DevDataSeeder

### الخطوة 8: الاختبار والتحقق
1. SeedDataValidationTest

---

## الملاحظات المهمة

### 1. كلمات المرور
- جميع كلمات المرور يجب أن تلبي متطلبات التعقيد:
  - 8+ حروف
  - حرف كبير واحد على الأقل
  - حرف صغير واحد على الأقل
  - رقم واحد على الأقل
  - رمز خاص واحد على الأقل

### 2. العلاقات
- يجب التأكد من صحة جميع العلاقات بين الكيانات
- استخدام foreign keys الصحيحة
- التحقق من cascade delete عند الحاجة

### 3. Value Objects
- استخدام AcademicId للرقم الأكاديمي
- استخدام EmailAddress للبريد الإلكتروني
- استخدام FullName للاسم الكامل
- استخدام GradePoint للـ GPA

### 4. Enums
- استخدام Role enum للأدوار
- استخدام UserStatus enum لحالة المستخدم
- استخدام AcademicStanding enum للوضع الأكاديمي
- استخدام EnrollmentStatus enum لحالة التسجيل

### 5. Domain Events
- رفع UserRegistered عند إنشاء مستخدم جديد
- رفع StudentEnrolled عند تسجيل طالب في مقرر
- رفع GradeRecorded عند تسجيل درجة

### 6. البيئة
- هذا الـ seeder للبيئة التطويرية فقط (APP_ENV=local)
- يجب عدم استخدامه في الإنتاج
- البيئة الأكاديمية: الجامعات اليمنية (صنعاء، عدن، ذمار، الحديدة، إب)
- يمكن إنشاء seeder منفصل للإنتاج ببيانات مختلفة

---

## التحقق من القواعد

✅ لا mock data في production code
✅ استخدام Factories للبيانات
✅ البيانات واقعية للبيئة الأكاديمية اليمنية
✅ استخدام Value Objects الصحيحة
✅ اتباع الـ DDD Layer Rules
✅ strict_types, final classes, full type hints
✅ استخدام Domain Events عند الحاجة
✅ العلاقات الصحيحة بين الكيانات
✅ استخدام Enums الصحيحة
✅ كلمات مرور معقدة
