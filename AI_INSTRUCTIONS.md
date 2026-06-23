# 🤖 AI ASSISTANT SYSTEM INSTRUCTIONS
## رفيق الطالب — Student Success Platform
**Version:** 1.0 | **Last Updated:** 2026-06-18 | **Authority:** Chief Architect
**Scope:** Mandatory for ALL AI coding assistants (Gemini, Claude, GPT, Copilot, etc.)

> These instructions override any default AI behavior. Non-compliance produces code that will be **rejected in review**.

---

## 🔴 BEFORE WRITING ANY CODE — READ THESE FIRST

### Step 1: Identify the Layer
```
Route Request → Controller (Presentation)
             → Use Case (Application)
             → Domain (Entities, Value Objects, Events)
             → Repository Interface (Domain Contract)
             → Repository Implementation (Infrastructure)
             → Database
```

### Step 2: Check These 5 Rules Every Time

| # | Rule | Violation = |
|---|------|------------|
| 1 | No mock/hardcoded data in Views | CRITICAL block |
| 2 | All Views use `@extends('layouts.dashboard')` | HIGH block |
| 3 | Only approved colors from design tokens | MEDIUM block |
| 4 | External services behind Interface contracts | HIGH block |
| 5 | `declare(strict_types=1)` in every PHP file | MEDIUM block |

---

## 📋 MANDATORY CHECKLIST — Run Before Every Code Output

```
ARCHITECTURE
[ ] Controller calls exactly ONE Use Case
[ ] Use Case has a single execute() method
[ ] No Eloquent or DB:: in Domain layer
[ ] No business logic in Views or Controllers
[ ] No direct DB access in Controllers

VIEWS
[ ] Uses @extends('layouts.dashboard') — NO standalone HTML
[ ] All data comes from $variable passed by Controller (real DB data)
[ ] Empty state included for every list (@if count > 0 / @else empty state)
[ ] No hardcoded Arabic text as data (labels/titles OK, values NO)
[ ] No @foreach(['item1', 'item2'] as $item) with demo content

DATA INTEGRITY
[ ] Zero fake(), Str::random(), rand() in production code
[ ] Zero hardcoded numbers/names/dates as content values
[ ] Zero mock arrays in Controllers or Use Cases
[ ] Seeders only for config/lookup data or test environments

DESIGN SYSTEM
[ ] Colors ONLY from approved palette (see below)
[ ] No Tailwind color classes (text-blue-500, bg-green-100, etc.)
[ ] No inline style= for colors or spacing
[ ] Badges use .badge .badge-{variant} from app.css
[ ] Progress bars use .progress-track + .progress-fill-{variant}
[ ] Cards use .dashboard-card .rafiq-card-shadow

EXTERNAL SERVICES
[ ] SDK imports (OpenAI, Mailgun, Stripe, etc.) ONLY in Infrastructure/
[ ] Use Cases depend on interfaces, not concrete implementations
[ ] No hardcoded API keys anywhere
[ ] All credentials read via config(), not getenv() or $_ENV

PHP CODE
[ ] declare(strict_types=1); at top of every file
[ ] final class for all Use Cases and Controllers
[ ] readonly properties on all DTOs
[ ] Full type hints (parameters + return types)
[ ] Constructor injection only (no new Foo() in business code)
```

---

## 🎨 APPROVED COLOR PALETTE — EXACT VALUES ONLY

```
PRIMARY BLUE    #243B7C   → main buttons, headings, active states
NAVY DEEP       #06214B   → AI cards, featured sections, hero backgrounds
EMERALD GREEN   #10B981   → success, completion, positive stats
WARNING GOLD    #F59E0B   → alerts, GPA warnings, deadlines
ERROR RED       #EF4444   → destructive actions, critical errors ONLY
BACKGROUND      #F8FAFC   → page backgrounds
SURFACE         #FFFFFF   → card backgrounds
BORDER          #E2E8F0   → all borders and dividers
TEXT PRIMARY    #1E293B   → main content text
TEXT SECONDARY  #64748B   → labels, subtitles
TEXT MUTED      #94A3B8   → placeholders, timestamps
```

**Usage in Tailwind:**
```html
<!-- ✅ CORRECT — using approved hex values -->
<div class="bg-[#243B7C] text-white">Primary Button</div>
<div class="bg-[#06214B] text-white">Navy Card</div>
<div class="bg-[#243B7C]/10 text-[#243B7C]">Light Primary Badge</div>
<div class="bg-[#E2E8F0] text-[#1E293B]">Border Element</div>

<!-- ❌ WRONG — Tailwind color names -->
<div class="bg-blue-700 text-white">Never do this</div>
<div class="bg-indigo-900">Or this</div>

<!-- ❌ WRONG — unapproved hex values -->
<div class="bg-[#7C3AED]">Purple is not in palette</div>
<div style="color: #FF6B6B">Inline styles forbidden</div>
```

**Visual Hierarchy Rule:**
- 75% Neutral (backgrounds, surfaces, text)
- 15% Primary `#243B7C`
- 5% Accent `#10B981`
- 3% Warning `#F59E0B`
- 2% Navy `#06214B` — premium/AI elements only

---

## 🗂️ VIEW TEMPLATE — Use for Every New Page

```blade
@extends('layouts.dashboard')
@section('title', 'اسم الصفحة')
@section('content')

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
    <div>
        <h1 class="text-xl font-black text-[#1E293B]">عنوان الصفحة</h1>
        <p class="text-sm text-[#64748B] mt-0.5">وصف مختصر</p>
    </div>
    {{-- Primary CTA Button --}}
    <a href="{{ route('module.action') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#243B7C] text-white text-sm font-bold
              hover:bg-[#1E2F63] transition-all shadow-md shadow-[#243B7C]/20 self-start sm:self-auto">
        إجراء رئيسي
    </a>
</div>

{{-- Content with Real Data --}}
@if($items->count() > 0)
    <div class="space-y-3">
        @foreach($items as $i => $item)
        <div class="dashboard-card bg-white border border-[#E2E8F0] rounded-2xl p-5 rafiq-card-shadow
                    animate-fade-in-up hover-lift"
             style="animation-delay:{{ ($i % 8) * 50 }}ms">
            {{-- Real data from $item DTO --}}
            <p class="text-sm font-black text-[#1E293B]">{{ $item->title }}</p>
        </div>
        @endforeach
    </div>
@else
    {{-- Empty State --}}
    <div class="bg-white border border-[#E2E8F0] rounded-2xl p-16 text-center rafiq-card-shadow animate-scale-in">
        <div class="w-20 h-20 rounded-2xl bg-[#EEF1FB] flex items-center justify-center mx-auto mb-5">
            <svg class="h-10 w-10 text-[#243B7C]/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <p class="text-base font-black text-[#1E293B] mb-2">لا توجد عناصر بعد</p>
        <p class="text-sm text-[#94A3B8] mb-6">أضف أول عنصر للبدء</p>
        <a href="{{ route('module.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#243B7C] text-white text-sm font-bold
                  hover:bg-[#1E2F63] transition-all shadow-lg shadow-[#243B7C]/20">
            إنشاء جديد
        </a>
    </div>
@endif

@endsection
```

---

## ⚙️ CONTROLLER TEMPLATE — Every New Controller

```php
<?php

declare(strict_types=1);

namespace Src\Modules\{Module}\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Src\Modules\{Module}\Application\UseCases\Get{Resource}List;
use Src\Modules\{Module}\Application\DTOs\{Resource}ListDto;

final class {Resource}Controller extends Controller
{
    public function __construct(
        private readonly Get{Resource}List $getList,
    ) {}

    public function index(Request $request): View
    {
        // ✅ Get authenticated student ID from session
        $studentId = auth()->id();

        // ✅ Real data from Use Case only
        $items = $this->getList->execute($studentId);

        // ✅ Pass real DTO to view
        return view('{module}.index', compact('items'));
    }
}
```

---

## 🔌 EXTERNAL SERVICE TEMPLATE — First Time Adding Any Provider

```php
// STEP 1: Define the contract in Domain
// src/Modules/Shared/Domain/Contracts/AiAdvisorInterface.php

<?php

declare(strict_types=1);

namespace Src\Modules\Shared\Domain\Contracts;

interface AiAdvisorInterface
{
    public function generateInsight(string $prompt): string;
    public function analyzeRisk(array $academicData): RiskAssessmentDto;
}
```

```php
// STEP 2: Implement in Infrastructure (NOT Domain or Application)
// src/Modules/Shared/Infrastructure/Providers/OpenAiAdvisor.php

<?php

declare(strict_types=1);

namespace Src\Modules\Shared\Infrastructure\Providers;

use Src\Modules\Shared\Domain\Contracts\AiAdvisorInterface;

final class OpenAiAdvisor implements AiAdvisorInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model,
    ) {}

    public function generateInsight(string $prompt): string
    {
        // OpenAI SDK calls here ONLY
    }
}
```

```php
// STEP 3: Bind in ServiceProvider
// app/Providers/ServiceBindingProvider.php

$this->app->bind(AiAdvisorInterface::class, fn() => new OpenAiAdvisor(
    apiKey: config('services.ai.key'),
    model: config('services.ai.model'),
));
```

```php
// STEP 4: Use Case depends ONLY on the interface
final class GenerateStudentInsight
{
    public function __construct(
        private readonly AiAdvisorInterface $aiAdvisor, // ✅ Interface, not OpenAiAdvisor
    ) {}
}
```

---

## ❌ INSTANT REJECTION PATTERNS — Never Generate These

```blade
{{-- ❌ Standalone HTML in a view file --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>...</head>
<body>...</body>
</html>

{{-- ❌ Hardcoded data values --}}
<p>المعدل: 3.75</p>
<p>96 / 144 ساعة</p>
@foreach(['مهمة 1', 'مهمة 2', 'مهمة 3'] as $task)

{{-- ❌ Unapproved colors --}}
<div class="bg-blue-500 text-purple-700">
<div style="background: #112233">
```

```php
// ❌ Faker/random in production code
$student->gpa = fake()->randomFloat(2, 2.0, 4.0);
$task->title = Str::random(10);

// ❌ Mock array in Controller
return view('dashboard', [
    'stats' => ['total' => 42, 'active' => 7], // hardcoded!
]);

// ❌ Provider SDK in Use Case
use OpenAI\Client;
final class GenerateInsight {
    public function __construct(private readonly Client $openai) {}
}

// ❌ DB:: in Domain
use Illuminate\Support\Facades\DB;
final class Student {
    public function getGrades(): array {
        return DB::table('grades')->get(); // NEVER in Domain
    }
}
```

---

## 📁 MODULE STRUCTURE REFERENCE

```
src/Modules/{ModuleName}/
├── Domain/
│   ├── Entities/           ← Pure PHP, no Laravel
│   ├── ValueObjects/       ← readonly, immutable
│   ├── Events/             ← Simple data classes
│   ├── Exceptions/         ← Domain-specific errors
│   └── Contracts/          ← Repository + Service interfaces
├── Application/
│   ├── UseCases/           ← One class = one operation
│   ├── DTOs/               ← readonly properties
│   └── Commands/Queries/
├── Infrastructure/
│   ├── Repositories/       ← Eloquent implementations
│   ├── Providers/          ← External service implementations
│   └── Models/             ← Eloquent models ONLY here
└── Presentation/
    ├── Http/
    │   ├── Controllers/    ← final, thin, calls one Use Case
    │   └── Requests/       ← Form validation
    └── Resources/          ← API output transformers
```

---

## 🔑 KEY FILES TO CHECK BEFORE ADDING FEATURES

| What to add | Check these files first |
|------------|------------------------|
| New UI page | `resources/views/layouts/dashboard.blade.php` (understand layout) |
| New colors | `resources/css/app.css` (check existing tokens) |
| New CSS utilities | `resources/css/app.css` (avoid duplication) |
| New Use Case | `.memory/architecture.md` (module boundaries) |
| New external integration | `ENGINEERING_RULEBOOK.md §12` (interface pattern) |
| New DB table | `.memory/coding-standards.md §4` (migration rules) |
| New module | `.memory/domain-glossary.md` (naming conventions) |

---

## 🧪 TESTING REQUIREMENTS

Every new feature needs:
1. **Unit test** for the Use Case (`tests/Unit/`)
2. **Feature test** for the Controller endpoint (`tests/Feature/`)
3. Tests use **Factories** for data — never hardcoded arrays
4. Test class: `final class`, method: `test_it_does_something(): void`

```php
// ✅ CORRECT test data
$student = StudentFactory::new()->withGpa(3.75)->create();

// ❌ WRONG — hardcoded in test
$this->actingAs(User::find('some-hardcoded-uuid'));
```

---

*Last updated by: AI Architecture Session — 2026-06-18*
*These instructions are part of the project's living documentation.*
*Update this file when engineering decisions change.*
