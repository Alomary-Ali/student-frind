# Responsive Design Checklist
## رفيق الطالب — يجب اجتيازها قبل أي Merge

---

## Breakpoints المطلوبة

| الـ Breakpoint | العرض | يجب اختباره على |
|----------------|-------|----------------|
| Mobile | 375px | iPhone SE / Galaxy S8 |
| Mobile L | 425px | iPhone 14 Pro |
| Tablet | 768px | iPad Mini |
| Laptop | 1024px | Surface Pro |
| Desktop | 1440px | MacBook Pro |

---

## Checklist لكل صفحة

### Navigation
- [ ] Sidebar يظهر بـ hamburger button على موبايل (< 1024px)
- [ ] Hamburger button يفتح السايدبار ويظهر overlay خلفه
- [ ] النقر على overlay يغلق السايدبار
- [ ] كل روابط الـ nav تعمل على موبايل
- [ ] تسجيل الخروج يعمل على موبايل

### Layout
- [ ] لا يوجد horizontal scroll في أي breakpoint
- [ ] لا يوجد overflow مخفي يكسر الـ layout
- [ ] المحتوى لا يخرج من حدود الـ container
- [ ] Images و cards تتكيف مع عرض الشاشة

### Typography & Spacing
- [ ] النص مقروء على موبايل (min 14px)
- [ ] Padding كافٍ حول المحتوى (min 16px)
- [ ] الـ headings تتناسب مع المساحة
- [ ] أرقام وبيانات عربية تظهر صحيحاً (RTL)

### Interactive Elements
- [ ] كل الأزرار min-height 44px على موبايل (WCAG)
- [ ] Hover states لا تُستبدل بشكل مكسور على touch
- [ ] Forms تعمل على موبايل (keyboard لا يغطي المدخلات)
- [ ] Dropdowns وقوائم تعمل على touch

### Cards & Grids
- [ ] Grid يتحول من 4 أعمدة → 2 → 1 عند التصغير
- [ ] Cards لا تنكسر على 375px
- [ ] Tables تحصل على horizontal scroll عند الحاجة

### RTL Specifics
- [ ] النص يبدأ من اليمين دائماً
- [ ] Sidebar يظهر على اليمين
- [ ] الأيقونات والـ chevrons في الاتجاه الصحيح
- [ ] Animations تتحرك بالاتجاه الصحيح (RTL)
- [ ] `border-r-4` للـ alert bars (يمين في RTL)

---

## أدوات الاختبار

```bash
# Chrome DevTools — Device Toolbar
# Firefox Responsive Design Mode

# Automated
npm run test:responsive  # إذا مُضاف

# Manual
open http://localhost:8000/academic/dashboard
# → تحقق على كل breakpoint
```

---

## ممنوع في كل وقت

```html
<!-- ❌ fixed width يكسر المحمول -->
<div style="width: 800px">

<!-- ❌ translate-x سالب في RTL -->
class="hover:translate-x-[-4px]"

<!-- ❌ navigation مخفية بدون بديل -->
class="hidden lg:hidden"   <!-- لا يُرى على أي شاشة! -->
```
