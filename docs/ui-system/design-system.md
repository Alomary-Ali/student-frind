# Design System
## رفيق الطالب — نظام التصميم الرسمي

---

## 1. الألوان

| الاسم | القيمة | الاستخدام |
|-------|--------|----------|
| `primary` | `#243B7C` | CTAs, active states, brand |
| `primary-hover` | `#1E2F63` | Hover على primary elements |
| `accent` | `#F59E0B` | Highlights, badges, awards |
| `success` | `#10B981` | Completed, active, positive |
| `error` | `#EF4444` | Errors, deletions, alerts |
| `warning` | `#F97316` | Cautions (مختلف عن accent) |
| `background` | `#F8FAFC` | Page background |
| `surface` | `#FFFFFF` | Cards, panels |
| `text-primary` | `#111827` | Body text |
| `text-secondary` | `#6B7280` | Hints, labels, metadata |
| `border` | `#E5E7EB` | Dividers, card borders |

> **قاعدة:** استخدم Tailwind tokens فقط. لا `text-blue-500`, لا `#FF0000` مباشرة.

---

## 2. Typography

**الخط:** Cairo (Google Fonts) — وزن 300 → 900

```html
<!-- إلزامي في كل layout -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap">
```

| المستوى | الصف | الاستخدام |
|---------|-----|----------|
| H1 | `text-2xl font-black` | Page title (مرة واحدة فقط) |
| H2 | `text-lg font-bold` | Section titles |
| H3 | `text-base font-semibold` | Card titles |
| Body | `text-sm font-normal` | Content |
| Small | `text-xs` | Labels, metadata |
| Micro | `text-[10px]` | Badges, timestamps |

---

## 3. Spacing

| Token | القيمة | Tailwind |
|-------|--------|---------|
| xs | 4px | `p-1`, `gap-1` |
| sm | 8px | `p-2`, `gap-2` |
| md | 12px | `p-3`, `gap-3` |
| lg | 16px | `p-4`, `gap-4` |
| xl | 24px | `p-6`, `gap-6` |
| 2xl | 32px | `p-8`, `gap-8` |

---

## 4. Components

### Card
```html
<div class="dashboard-card bg-surface border border-border rounded-2xl p-6 rafiq-card-shadow">
    <!-- content -->
</div>
```

### KPI Stat Card
```html
<div class="bg-surface border border-border rounded-2xl p-4">
    <div class="flex justify-between items-start mb-2">
        <span class="text-xs text-text-secondary">العنوان</span>
        <span class="p-2 rounded-lg bg-primary/10 text-primary"><!-- icon --></span>
    </div>
    <div class="text-2xl font-bold text-text-primary">42</div>
    <p class="text-xs text-text-secondary mt-1">وصف</p>
</div>
```

### Badge / Status
```html
<!-- Active / Success -->
<span class="px-2 py-1 rounded-full text-[10px] font-medium bg-success/10 text-success">مكتمل</span>

<!-- Pending -->
<span class="px-2 py-1 rounded-full text-[10px] font-medium bg-primary/10 text-primary">جارٍ</span>

<!-- Error -->
<span class="px-2 py-1 rounded-full text-[10px] font-medium bg-error/10 text-error">متأخر</span>
```

### Alert / Warning Bar
```html
<!-- RTL: border-r-4 = الحد يظهر على اليمين -->
<div class="p-4 bg-background rounded-lg border-r-4 border-warning">
    <p class="text-sm text-text-primary">رسالة التنبيه</p>
</div>
```

### Primary Button
```html
<button class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold text-sm transition-colors">
    تأكيد
</button>
```

### Secondary Button
```html
<button class="px-6 py-2.5 rounded-xl bg-primary/10 hover:bg-primary/20 text-primary font-bold text-sm transition-colors">
    إلغاء
</button>
```

### Empty State
```html
<div class="text-center py-12">
    <svg class="h-14 w-14 mx-auto text-border mb-4" ...></svg>
    <h3 class="text-base font-semibold text-text-primary mb-2">لا توجد عناصر</h3>
    <p class="text-sm text-text-secondary mb-6">وصف الحالة الفارغة</p>
    <a href="#" class="inline-block px-6 py-2.5 rounded-xl bg-primary text-white font-bold text-sm">
        إضافة جديد
    </a>
</div>
```

---

## 5. Animations

```css
/* مسموح */
transition-colors   /* للألوان */
transition-all duration-200  /* للـ cards */
.hover-slide-in:hover { transform: translateX(4px); }  /* RTL-safe */

/* ممنوع */
hover:translate-x-[-4px]   /* اتجاه خاطئ في RTL */
animation: spin 99s         /* مبالغة */
```

---

## 6. قواعد UI الإلزامية

| القاعدة | السبب |
|---------|-------|
| كل صفحة `@extends('layouts.dashboard')` | Layout موحّد |
| لا hardcoded data في Views | يكسر التطبيق |
| لا inline colors (`style="color:#..."`) | يكسر Dark Mode |
| `aria-label` على كل أيقونة بدون نص | إتاحة WCAG |
| Empty States على كل list | UX جيد |
| `rafiq-card-shadow` على كل card | Consistency |
