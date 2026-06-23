# 📘 ENGINEERING RULEBOOK
## رفيق الطالب — Student Success Platform
**الإصدار:** 3.0 | **آخر تحديث:** 2026-06-18 | **الحالة:** إلزامي

> أي كود يخالف هذه القواعد يُعتبر **Technical Debt** ولا يُقبل في أي Pull Request.

---

## 1. قواعد الأمان 🔐 (غير قابلة للتفاوض)

### ✅ مسموح
- كل routes تحتاج مصادقة → تحت `Route::middleware('auth')`
- كل routes للضيوف فقط → تحت `Route::middleware('guest')`
- Rate limiting إلزامي على login → `throttle:5,1`
- Logout عبر POST + CSRF فقط
- `SESSION_ENCRYPT=true` في production
- `APP_DEBUG=false` في production
- كلمات المرور بـ bcrypt/argon2 فقط (`password_hash` field)

### ❌ ممنوع منعاً باتاً
- Routes بدون auth middleware للصفحات المحمية
- ملفات PHP فضفاضة في جذر المشروع (debug scripts)
- كلمات مرور أو API keys مكتوبة مباشرة في الكود
- `APP_DEBUG=true` في production
- `DB_PASSWORD=` فارغ في production
- تجاوز CSRF بأي طريقة
- Raw SQL queries خارج Repositories

---

## 2. قواعد المعمارية 🏛️

### القاعدة الأساسية: Clean Architecture Flow
```
Presentation → Application → Domain ← Infrastructure
```

### طبقة Domain (src/Modules/*/Domain/)
- ✅ Entities مع `final` keyword
- ✅ Value Objects مع `readonly` properties
- ✅ Domain Events
- ✅ Repository Interfaces (لا implementations)
- ✅ Domain Exceptions
- ❌ لا Eloquent, لا DB facades, لا HTTP

### طبقة Application (src/Modules/*/Application/)
- ✅ Use Cases بـ `execute()` method واحد
- ✅ DTOs مع `readonly` properties
- ✅ Commands/Queries
- ❌ لا business logic في DTOs
- ❌ لا HTTP requests/responses

### طبقة Infrastructure (src/Modules/*/Infrastructure/)
- ✅ Eloquent repositories تُنفِّذ Domain Interfaces
- ✅ EloquentModel منفصلة عن Domain Entities
- ✅ Mappers بين Eloquent ↔ Domain
- ❌ لا Domain logic

### طبقة Presentation (src/Modules/*/Presentation/)
- ✅ Controllers تستدعي Use Cases فقط
- ✅ Requests لـ validation
- ✅ Resources لـ transforming output
- ❌ لا business logic في Controllers
- ❌ لا direct DB access
- ❌ لا View rendering مباشرة من Routes (استخدم Controllers)

---

## 3. قواعد الكود 📝

### Naming Conventions
| العنصر | الصيغة | مثال |
|--------|--------|------|
| Class | PascalCase | `StudentRepository` |
| Method | camelCase | `findByAcademicId()` |
| Variable | camelCase | `$activeGoals` |
| Constant | UPPER_SNAKE | `MAX_ENROLLMENT_HOURS` |
| Table | snake_case plural | `academic_students` |
| Migration | timestamp_verb_noun | `2026_06_20_create_users_table` |

### قواعد إلزامية
```php
declare(strict_types=1);   // ✅ في كل ملف PHP
```
- `final class` لكل Use Case وController
- `readonly` لكل DTO property
- Dependency Injection دائماً (لا `new` في constructors)
- Type hints كاملة (parameters + return types)
- لا `array` بدون type hint `@var array<int, User>`

### ممنوع
```php
// ❌ Business logic في Views
@if($user->gpa > 2.0 && $user->status === 'active') 

// ❌ Direct DB في Controllers  
$students = DB::table('users')->get();

// ❌ Hardcoded data في Views
<p>GPA: 3.75</p>

// ❌ God Controller (أكثر من 3 dependencies)
public function __construct(
    private StudentRepo $students,
    private CourseRepo $courses,
    private GradeRepo $grades,
    private EmailService $email,
    private SmsService $sms,
) {}
```

---

## 4. قواعد قاعدة البيانات 🗄️

### إلزامي في كل Migration
```php
$table->uuid('id')->primary();          // ✅ UUID دائماً
$table->uuid('user_id');                // ✅ FK field
$table->foreign('user_id')             // ✅ FK constraint إلزامي
      ->references('id')->on('users')->cascadeOnDelete();
$table->timestamps();                   // ✅ دائماً
$table->softDeletes();                  // ✅ للبيانات المهمة
$table->index('user_id');               // ✅ index على كل FK
```

### ممنوع
```php
// ❌ Raw DROP TABLE
DB::statement('DROP TABLE users');

// ❌ Migration بدون rollback
public function down(): void {} // فارغة

// ❌ Enum كـ string بدون constraint
$table->string('status'); // استخدم ->enum()

// ❌ Missing Foreign Key
$table->uuid('user_id'); // بدون ->foreign()
```

---

## 5. قواعد API 🌐

### Standard Response Contract
```json
{
  "success": true,
  "data": { },
  "message": "Operation successful.",
  "errors": null,
  "meta": { "page": 1, "per_page": 20, "total": 100 }
}
```

### إلزامي في كل API
- **Versioning:** `/api/v1/resource`
- **Auth:** `auth:sanctum` middleware
- **Pagination:** كل list endpoints لازم `per_page` + `page`
- **Validation:** FormRequest لكل POST/PUT
- **HTTP Status:** 200/201/422/401/403/404/500

### ممنوع
- API بدون authentication (إلا `/login`)
- List endpoint يُعيد كل السجلات بدون pagination
- Error responses بدون error code معياري

---

## 6. قواعد الاختبارات 🧪

### Coverage Minimums (CI يفشل إذا انخفضت)
| الطبقة | الحد الأدنى |
|--------|-------------|
| Domain Layer | 90% |
| Application (Use Cases) | 85% |
| Infrastructure | 70% |
| Presentation (Controllers) | 70% |

### إلزامي
- كل Use Case له unit test
- كل Controller له feature test
- كل Domain Entity له unit test
- لا Merge بدون اجتياز كل الـ tests

### هيكل Test
```php
final class RecordAcademicGradeTest extends TestCase
{
    // Arrange → Act → Assert
    public function test_it_records_grade_and_updates_gpa(): void
    {
        // Arrange
        $student = StudentFactory::create();
        
        // Act
        $result = $this->useCase->execute($dto);
        
        // Assert
        $this->assertSame(3.5, $result['cumulative_gpa']);
    }
}
```

---

## 7. قواعد UI/UX 🎨

### نظام الألوان الرسمي المعتمد — Design Tokens فقط

**الألوان الأساسية المعتمدة في `app.css` (`@theme` و `:root`):**

| الرمز | القيمة | الاستخدام |
|-------|--------|----------|
| `--color-primary` | `#243B7C` | العناوين، الأزرار الرئيسية |
| `--color-primary-hover` | `#1E2F63` | تحويم الأزرار الرئيسية |
| `--color-primary-active` | `#18234E` | ضغط الأزرار |
| `--color-primary-light` | `#EEF1FB` | خلفية البطاقات الفاتحة |
| `--color-navy` | `#06214B` | البطاقات المميزة، أقسام AI |
| `--color-navy-hover` | `#041932` | تحويم العناصر النافيّة |
| `--color-accent` | `#10B981` | النجاح، الإنجاز، الإحصائيات |
| `--color-accent-hover` | `#059669` | تحويم |
| `--color-accent-active` | `#047857` | ضغط |
| `--color-accent-light` | `#D1FAE5` | خلفية فاتحة |
| `--color-warning` | `#F59E0B` | التنبيهات، المعدل، الانتباه |
| `--color-warning-hover` | `#D97706` | تحويم |
| `--color-warning-light` | `#FEF3C7` | خلفية فاتحة |
| `--color-error` | `#EF4444` | الأخطاء، الحذف فقط |
| `--color-error-hover` | `#DC2626` | تحويم |
| `--color-error-light` | `#FEE2E2` | خلفية فاتحة |
| `--color-background` | `#F8FAFC` | خلفية الصفحات |
| `--color-surface` | `#FFFFFF` | خلفية البطاقات |
| `--color-surface-raised` | `#FAFBFF` | بطاقات مرتفعة قليلاً |
| `--color-text-primary` | `#1E293B` | النصوص الرئيسية |
| `--color-text-secondary` | `#64748B` | النصوص الثانوية |
| `--color-text-muted` | `#94A3B8` | النصوص الخافتة |
| `--color-border` | `#E2E8F0` | الحدود الرئيسية |
| `--color-border-light` | `#F1F5F9` | الحدود الفاتحة |

**قواعد استخدام الألوان:**
```css
/* ✅ مسموح — Design Tokens عبر CSS variables */
var(--color-primary)
var(--color-accent)
var(--color-navy)

/* ✅ مسموح — Tailwind utility classes (V4 generates them from @theme) */
class="bg-primary text-accent border-border"
class="bg-primary/10 text-accent/80"

/* ✅ مسموح — Tailwind arbitrary مع القيم المعتمدة فقط */
class="bg-[#243B7C] text-[#06214B] border-[#E2E8F0]"

/* ✅ مسموح — Tailwind opacity modifier على الألوان المعتمدة */
class="bg-[#243B7C]/10 text-[#10B981]/80"

/* ❌ ممنوع — ألوان Tailwind مباشرة (غير معرّفة في @theme) */
class="text-blue-500 bg-green-100 border-red-300"

/* ❌ ممنوع — ألوان غير معتمدة في المشروع */
style="color: #FF6B6B"
class="bg-purple-400"

/* ❌ ممنوع — inline styles للألوان */
style="color: #FF0000; background: blue"
```

### نظام التدرجات اللونية (Gradients)

| الرمز | القيمة | الاستخدام |
|-------|--------|----------|
| `--gradient-primary` | `135deg, #243B7C → #1E2F63` | الأزرار الرئيسية، البطاقات |
| `--gradient-navy` | `135deg, #06214B → #06214B` | بطاقات AI |
| `--gradient-accent` | `135deg, #10B981 → #059669` | أزرار النجاح |
| `--gradient-warm` | `135deg, #F59E0B → #D97706` | التنبيهات |
| `--gradient-hero` | `135deg, #06214B → #243B7C` | الأقسام الرئيسية |
| `--gradient-surface` | `180deg, #FFFFFF → #F8FAFC` | البطاقات |
| `--gradient-mist` | `135deg, #F8FAFC → #EEF1FB` | الخلفيات الناعمة |

### مقياس الخطوط (Typography Scale)

استخدام `@theme` في `app.css`:

| الكلاس | الحجم | الاستخدام |
|--------|-------|-----------|
| `text-caption` | `0.75rem` (12px) | التواريخ، الأرقام الصغيرة |
| `text-body` | `0.875rem` (14px) | النصوص العادية |
| `text-body-lg` | `1rem` (16px) | نصوص موسعة |
| `text-subtitle` | `1.125rem` (18px) | العناوين الفرعية |
| `text-title` | `1.5rem` (24px) | العناوين الرئيسية |
| `text-display` | `2rem` (32px) | العناوين الكبيرة |

**ملاحظة:** `text-sm` (14px) و `text-xs` (12px) و `text-lg` (18px) و `text-2xl` (24px) من Tailwind تستخدم أيضاً مع المقياس أعلاه.

### نظام البطاقات (Card System)

| الكلاس | الاستخدام | الخصائص |
|--------|-----------|---------|
| `.card` | بطاقة عادية | surface bg, border, rounded-2xl |
| `.card-elevated` | بطاقة مرتفعة | surface bg, border, shadow |
| `.card-navy` | بطاقة داكنة (AI) | navy gradient, shadow |
| `.card-primary` | بطاقة أساسية | primary gradient, shadow |
| `.card-info` | بطاقة معلومات | primary-light bg, primary border |
| `.card-success` | بطاقة نجاح | accent-light bg, accent border |
| `.card-warning` | بطاقة تنبيه | warning-light bg, warning border |
| `.card-error` | بطاقة خطأ | error-light bg, error border |
| `.dashboard-card` | بطاقة dashboard (أداء) | will-change, backface-visibility |

### نظام الأزرار (Button System)

| الكلاس | الاستخدام | الوصف |
|--------|-----------|-------|
| `.btn` | القاعدة | flex, gap-2, h-12, px-6, rounded-xl, font-bold |
| `.btn-primary` | الإجراء الرئيسي | gradient primary, shadow |
| `.btn-secondary` | الإجراء الثانوي | surface bg, border |
| `.btn-accent` | إجراء نجاح | gradient accent, shadow |
| `.btn-ghost` | إجراء بسيط | transparent, border |
| `.btn-navy` | إجراء AI | gradient navy, shadow |
| `.btn-full` | عرض كامل (معدّل) | `width: 100%` |

**قواعد:**
- أزرار Auth تستخدم `btn btn-primary btn-full`
- أزرار Dashboard تستخدم `.btn` + أحد الـ variants
- استخدام `<x-button>` للمكونات البرمجية (يدعم variant: primary/secondary/ghost/destructive)

### مكون Empty State

لمنع عرض `collect()` أو `[]` للمستخدم مباشرة:

```blade
<x-empty-state
    title="لا توجد أهداف"
    description="ابدأ بإضافة هدفك الأول لتحقيق النجاح"
    action-label="إضافة هدف"
>
    <x-slot:action>
        <a href="{{ route('productivity.goals.create') }}" class="btn btn-primary btn-full">
            إضافة هدف
        </a>
    </x-slot:action>
</x-empty-state>
```

### إلزامي
- كل صفحة dashboard تستخدم `@extends('layouts.dashboard')` — لا استثناء
- `aria-label` على كل `<button>` بدون نص ظاهر
- الخط `Cairo` محمّل من Google Fonts في Layout
- Mobile sidebar موجود ويعمل في كل صفحة
- كل بطاقة داكنة (navy) يجب أن تستخدم `#06214B` فقط
- Progress bars تستخدم `.progress-track` + `.progress-fill-*` من `app.css`
- Badges تستخدم `.badge .badge-*` من `app.css`
- الحالات الفارغة تستخدم `<x-empty-state>` بدلاً من `collect()` مباشرة

### ممنوع
- صفحات standalone (HTML كاملة مع `<body>` بدون Blade layout)
- بيانات مُثبَّتة (hardcoded) في Views — انظر قسم 11
- Inline styles للألوان أو المسافات
- `hover:translate-x-[-Npx]` في RTL — استخدم `translate-x-[+Npx]` أو فئات `.interactive-row`
- كلاسات Tailwind للألوان خارج القائمة المعتمدة أعلاه
- تعريف CSS خاص بالصفحة يتعارض مع `app.css`
- استخدام `bg-success`, `text-success` (غير موجودة) — استخدم `bg-accent`, `text-accent`
- إعادة تعريف `.nav-link` أو `.nav-link-active` أو `.btn` أو `.badge` أو `.card` في `<style>` داخل الصفحة — كلها في `app.css`

---

## 8. قواعد DevOps 🚀

### قبل كل Merge
- [ ] `./vendor/bin/pint --test` → يمر
- [ ] `./vendor/bin/phpstan analyse` → يمر (Level 6+)
- [ ] `php artisan test` → 100% يمر
- [ ] Coverage >= 80%
- [ ] `composer audit` → لا vulnerabilities حرجة
- [ ] لا ملفات PHP فضفاضة في الجذر

### Production Checklist
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] `SESSION_ENCRYPT=true`
- [ ] `DB_PASSWORD` قوي ومعقد
- [ ] `QUEUE_CONNECTION=redis` (ليس database)
- [ ] `CACHE_STORE=redis` (ليس database)

---

## 9. Technical Debt Register

أي مخالفة تُضاف كـ GitHub Issue بـ label `tech-debt` وتحتوي:
- **الملف:** المسار الكامل
- **المشكلة:** وصف المخالفة
- **القاعدة المخالَفة:** رقم القاعدة من هذا الكتاب
- **الأولوية:** Critical / High / Medium / Low
- **المُكلَّف:** اسم المطور
- **الموعد:** تاريخ الإصلاح

---

## 10. مخالفات تمنع Merge فوراً 🚨

| المخالفة | السبب |
|---------|-------|
| Route بدون auth middleware | Security Critical |
| ملف PHP في جذر المشروع | Security Critical |
| كلمة مرور في الكود | Security Critical |
| `APP_DEBUG=true` في .env | Security Critical |
| Business logic في View | Architecture Violation |
| Direct DB في Controller | Architecture Violation |
| Test فاشل | Quality Gate |
| Coverage < 80% | Quality Gate |
| PHPStan errors | Code Quality |
| **Mock data / hardcoded data في View** | **Product Integrity — قسم 11** |
| **Hardcoded service provider** | **Provider-Agnostic Violation — قسم 12** |
| صفحة بدون `@extends('layouts.dashboard')` | UI Architecture Violation |
| لون غير معتمد في UI | Design System Violation |

---

## 11. قاعدة البيانات الحقيقية — No Mock Data 🚫

**هذا القسم غير قابل للتفاوض. يُعتبر كسره خطأ معماريًا حرجًا.**

### المبدأ
كل ميزة أو صفحة أو مكوّن في النظام يجب أن **يعمل ببيانات حقيقية فقط** قادمة من قاعدة البيانات عبر Use Cases ومستودعات (Repositories). لا يوجد مكان في النظام لبيانات وهمية أو عشوائية.

### ✅ مسموح
```php
// ✅ بيانات حقيقية من Use Case
$dashboard = $this->getDashboardStats->execute($studentId);
return view('academic.dashboard', compact('dashboard'));

// ✅ Factory في Tests فقط (ليس في Production code)
$student = StudentFactory::new()->create();

// ✅ Seeders للبيانات الأولية الضرورية (config data, lookup tables)
AcademicTermSeeder::class
```

### ❌ ممنوع منعاً باتاً
```php
// ❌ Hardcoded بيانات في View
<p>GPA: 3.75</p>
<p>الفصل الثاني 2026</p>
<div>96 / 144 ساعة</div>

// ❌ Mock arrays في Controller
$data = [
    'gpa' => 3.75,
    'hours' => 96,
    'tasks' => ['مراجعة البرمجة', 'إتمام التقرير'],
];
return view('dashboard', $data);

// ❌ Random/Faker في Production code
$gpa = fake()->randomFloat(2, 2.0, 4.0);
$task = Str::random(10);

// ❌ Static demo content في Views
@foreach(['مراجعة البرمجة', 'إتمام التقرير'] as $task)
```

### قاعدة "Empty State" الإلزامية
كل صفحة تعرض بيانات **يجب** أن تحتوي على:
1. **حالة البيانات الحقيقية** → عرض البيانات من DB
2. **حالة الفراغ (Empty State)** → رسالة واضحة + CTA للإنشاء
3. **حالة الخطأ** → معالجة مناسبة

```blade
{{-- ✅ النمط الصحيح --}}
@if($items->count() > 0)
    @foreach($items as $item)
        {{-- عرض البيانات الحقيقية --}}
    @endforeach
@else
    {{-- Empty State أنيق --}}
    <p>لا توجد عناصر بعد. <a href="...">أنشئ أول عنصر</a></p>
@endif
```

### Seeders المسموح بها
يُسمح فقط بـ Seeders من النوعين:

| النوع | الهدف | المثال |
|-------|--------|--------|
| **Config Seeders** | بيانات ثابتة ضرورية للنظام | `AcademicTermSeeder`, `CollegeSeeder` |
| **Test Seeders** | بيانات اختبار في `database/seeders/` مع `ENV=testing` | `TestUserSeeder` |

**ممنوع:** Seeders تضع بيانات وهمية في قاعدة بيانات Production.

---

## 12. قاعدة Provider-Agnostic — استقلالية مزودي الخدمة 🔌

**الهدف:** ضمان أن تغيير أي مزود خدمة خارجي (AI, Email, SMS, Storage, Payment, Auth) لا يتطلب تعديل Business Logic أو UI.

### المبدأ
كل تكامل خارجي يجب أن يمر عبر **Interface في Domain Layer** مع **Implementation في Infrastructure Layer**. لا يُكتب اسم مزود الخدمة مباشرة خارج `Infrastructure/`.

### ✅ البنية الصحيحة
```
Domain/Contracts/
├── AiAdvisorInterface.php       ← تعريف العقد
├── EmailSenderInterface.php
├── StorageInterface.php
└── SmsNotifierInterface.php

Infrastructure/Providers/
├── OpenAiAdvisor.php            ← implementation محدد
├── MailgunEmailSender.php
├── S3Storage.php
└── TwilioSmsNotifier.php
```

```php
// ✅ صحيح — Use Case يعتمد على Interface
final class GenerateStudentRecommendation
{
    public function __construct(
        private readonly AiAdvisorInterface $aiAdvisor,
    ) {}
}

// ✅ صحيح — Binding في ServiceProvider
$this->app->bind(AiAdvisorInterface::class, OpenAiAdvisor::class);
```

### ❌ ممنوع منعاً باتاً
```php
// ❌ استخدام SDK مباشر في Use Case
use OpenAI\Client;
use Illuminate\Mail\Mailer;
use League\Flysystem\Filesystem;

final class GenerateStudentRecommendation
{
    public function __construct(
        private readonly Client $openai, // ❌ مزود محدد
    ) {}
}

// ❌ API key مكتوب في الكود
$client = new OpenAI\Client('sk-hardcoded-key');

// ❌ اسم مزود في Domain/Application
use Mailgun\Mailgun;
use Stripe\Stripe;
```

### جدول مزودي الخدمة المعتمدين حاليًا

| الخدمة | المزود الحالي | البديل المحتمل | Interface |
|--------|--------------|----------------|----------|
| AI/LLM | (مُعرَّف لاحقًا) | OpenAI / Gemini / Claude | `AiAdvisorInterface` |
| Email | (مُعرَّف لاحقًا) | Mailgun / SES / SMTP | `EmailSenderInterface` |
| Storage | Local → S3 | R2 / MinIO | `FileStorageInterface` |
| Queue | Database → Redis | SQS / RabbitMQ | Laravel native |
| Cache | File → Redis | Memcached | Laravel native |

### قواعد Config الخارجية
```php
// ✅ كل credentials في .env فقط
AI_PROVIDER=openai
AI_API_KEY=sk-...
AI_MODEL=gpt-4o

// ✅ قراءة عبر config() فقط
$model = config('services.ai.model');

// ❌ ممنوع: مباشرة من $_ENV أو getenv() في Business Logic
$key = getenv('OPENAI_API_KEY'); // في Use Case
```

### قواعد UI مع الخدمات الخارجية
- لا يُعرَض اسم مزود الخدمة للمستخدم في UI ("Powered by OpenAI" ← ممنوع)
- كل رسائل الخطأ الخارجية تُترجم لرسائل عربية مفهومة قبل العرض
- Timeouts وretries تُعالج في Infrastructure Layer لا في View

---


