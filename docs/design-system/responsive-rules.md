# Responsive Rules — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Chief Product Designer / Design System Architect
**Status:** MANDATORY — All interfaces must be responsive

---

## Responsive Design Philosophy

**Desktop is NOT the primary target.**

The design must support:
- Mobile (320px - 480px)
- Tablet (481px - 1024px)
- Laptop (1025px - 1440px)
- Desktop (1441px - 1920px)
- Ultra-wide (1921px+)

All interfaces must be responsive by default.

---

## Breakpoint System

### Standard Breakpoints

| Breakpoint | Min Width | Max Width | Usage |
|------------|-----------|-----------|-------|
| mobile | 0px | 480px | Phones |
| sm | 481px | 640px | Large phones |
| md | 641px | 768px | Tablets |
| lg | 769px | 1024px | Small laptops |
| xl | 1025px | 1280px | Laptops |
| 2xl | 1281px | 1536px | Desktops |
| 3xl | 1537px+ | Ultra-wide |

### Tailwind Configuration

```javascript
screens: {
  'mobile': '0px',
  'sm': '481px',
  'md': '641px',
  'lg': '769px',
  'xl': '1025px',
  '2xl': '1281px',
  '3xl': '1537px',
}
```

---

## Responsive Behavior

### Adaptation Priority

When screen size decreases:

1. **Compress spacing first** — Reduce padding and margins
2. **Compress padding second** — Reduce component internal spacing
3. **Collapse layouts third** — Stack columns, hide sidebars
4. **Stack sections fourth** — Vertical layout for all content

### Forbidden Behaviors

**Never allow:**
- ❌ Horizontal scrolling
- ❌ Broken layouts
- ❌ Overflowing cards
- ❌ Overflowing tables
- ❌ Hidden content (content must remain accessible)

---

## Layout Patterns

### Container Widths

| Breakpoint | Max Content Width | Padding |
|------------|-------------------|---------|
| mobile | 100% | 16px |
| tablet | 100% | 24px |
| laptop | 1200px | 32px |
| desktop | 1440px | 48px |
| ultra-wide | 1440px | 64px |

### Container Example

```html
<div className="w-full max-w-[1440px] mx-auto px-4 md:px-6 lg:px-8 xl:px-12">
  Content
</div>
```

---

## Grid Systems

### Mobile Grid

```html
<!-- Single column on mobile -->
<div className="grid grid-cols-1 gap-4">
  <Card>Item 1</Card>
  <Card>Item 2</Card>
</div>
```

### Tablet Grid

```html
<!-- Two columns on tablet -->
<div className="grid grid-cols-1 md:grid-cols-2 gap-4">
  <Card>Item 1</Card>
  <Card>Item 2</Card>
</div>
```

### Desktop Grid

```html
<!-- Three columns on desktop -->
<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  <Card>Item 1</Card>
  <Card>Item 2</Card>
  <Card>Item 3</Card>
</div>
```

---

## Component Responsive Behavior

### Tables

**Desktop:** Standard table with horizontal scroll if needed

**Mobile:** Transform to card view

```html
<!-- Desktop table -->
<table className="hidden md:table w-full">
  <thead>
    <tr>
      <th>Name</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Item 1</td>
      <td>Active</td>
    </tr>
  </tbody>
</table>

<!-- Mobile card view -->
<div className="md:hidden space-y-4">
  <div className="p-4 border-border border rounded-lg">
    <div className="font-medium">Item 1</div>
    <div className="text-sm text-text-secondary">Active</div>
  </div>
</div>
```

### Forms

**Desktop:** Multi-column layout

**Mobile:** Stacked single column

```html
<!-- Multi-column on desktop, stacked on mobile -->
<div className="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label className="block mb-2">First Name</label>
    <input className="w-full" />
  </div>
  <div>
    <label className="block mb-2">Last Name</label>
    <input className="w-full" />
  </div>
</div>
```

### Navigation

**Desktop:** Top navigation bar

**Mobile:** Bottom navigation bar or hamburger menu

```html
<!-- Desktop top nav -->
<nav className="hidden md:flex items-center gap-4">
  <a href="/">Home</a>
  <a href="/courses">Courses</a>
</nav>

<!-- Mobile bottom nav -->
<nav className="md:hidden fixed bottom-0 left-0 right-0 bg-surface border-border border-t">
  <div className="flex justify-around py-2">
    <a href="/">Home</a>
    <a href="/courses">Courses</a>
  </div>
</nav>
```

### Cards

**Desktop:** Grid layout with multiple columns

**Mobile:** Single column, full width

```html
<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  <Card>Item 1</Card>
  <Card>Item 2</Card>
  <Card>Item 3</Card>
</div>
```

---

## Typography Responsive

### Font Sizes

| Breakpoint | H1 | H2 | H3 | Body |
|------------|----|----|----|------|
| mobile | 24px | 20px | 18px | 14px |
| tablet | 28px | 22px | 20px | 15px |
| desktop | 30px | 24px | 20px | 16px |

### Example

```html
<h1 className="text-2xl md:text-3xl font-semibold">
  Page Title
</h1>
```

---

## Spacing Responsive

### Padding

| Breakpoint | Card Padding | Section Gap |
|------------|--------------|-------------|
| mobile | 12px | 16px |
| tablet | 16px | 24px |
| desktop | 16px | 32px |

### Example

```html
<div className="p-3 md:p-4 space-y-4 md:space-y-8">
  Content
</div>
```

---

## Images Responsive

### Responsive Images

```html
<img
  src="image.jpg"
  alt="Description"
  className="w-full h-auto object-cover"
  loading="lazy"
/>
```

### Image Containers

```html
<div className="aspect-w-16 aspect-h-9">
  <img src="image.jpg" alt="Description" className="w-full h-full object-cover" />
</div>
```

---

## Touch Targets

### Minimum Touch Target Size

**Minimum:** 44px × 44px

**Recommended:** 48px × 48px

### Example

```html
<button className="min-h-[44px] min-w-[44px] p-2">
  Click me
</button>
```

---

## Responsive Testing

### Test Devices

- **Mobile:** iPhone SE, iPhone 14 Pro, Samsung Galaxy
- **Tablet:** iPad, iPad Pro, Samsung Tablet
- **Laptop:** MacBook Air, MacBook Pro
- **Desktop:** 1920×1080, 2560×1440

### Test Scenarios

1. **Orientation:** Portrait and landscape
2. **Zoom:** 150%, 200% zoom
3. **Text size:** Large text settings
4. **Touch:** Touch gestures, swipe actions

---

## Responsive Anti-Patterns

### ❌ Forbidden

```html
<!-- Fixed width containers -->
<div style="width: 1200px;">Content</div>

<!-- Horizontal scroll on body -->
<body style="overflow-x: auto;">Content</body>

<!-- Hidden content on mobile -->
<div className="hidden md:block">Important content</div>

<!-- Non-responsive images -->
<img src="image.jpg" width="1200" height="600" />
```

### ✅ Required

```html
<!-- Fluid containers -->
<div className="w-full max-w-[1440px] mx-auto">Content</div>

<!-- No horizontal scroll -->
<body className="overflow-x-hidden">Content</body>

<!-- Responsive content -->
<div className="block md:hidden">Mobile content</div>
<div className="hidden md:block">Desktop content</div>

<!-- Responsive images -->
<img src="image.jpg" className="w-full h-auto" alt="Description" />
```

---

## Tailwind Responsive Utilities

### Common Patterns

```html
<!-- Responsive display -->
<div className="hidden md:block">Desktop only</div>
<div className="block md:hidden">Mobile only</div>

<!-- Responsive flex direction -->
<div className="flex flex-col md:flex-row gap-4">
  <div>Column 1</div>
  <div>Column 2</div>
</div>

<!-- Responsive grid -->
<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  <Card>Item 1</Card>
  <Card>Item 2</Card>
  <Card>Item 3</Card>
</div>

<!-- Responsive text alignment -->
<div className="text-left md:text-center">
  Content
</div>

<!-- Responsive spacing -->
<div className="p-4 md:p-6 lg:p-8">
  Content
</div>
```

---

## Enforcement Rules

### Before Implementing UI

1. **Is it mobile-first?** Design for smallest screen first
2. **Does it scale up?** Test on all breakpoints
3. **Is content accessible?** No hidden content on mobile
4. **Are touch targets adequate?** Minimum 44px × 44px
5. **Is horizontal scroll prevented?** Never allow horizontal scroll

### Responsive Checklist

- ✅ Mobile-first approach
- ✅ Tested on all breakpoints
- ✅ No horizontal scrolling
- ✅ Touch targets adequate (44px × 44px minimum)
- ✅ Content remains accessible on all devices
- ✅ Tables transform to cards on mobile
- ✅ Forms stack on mobile
- ✅ Images are responsive
- ✅ Typography scales appropriately
- ✅ Spacing adapts to screen size

---

## References

- Design Principles: `design-principles.md`
- Color System: `color-system.md`
- Typography: `typography.md`
- Spacing: `spacing.md`
- Components: `components.md`
- Accessibility: `accessibility.md`
