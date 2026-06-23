# Spacing — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Chief Product Designer / Design System Architect
**Status:** MANDATORY — All spacing must comply

---

## Spacing Philosophy

The platform handles large amounts of academic data. Spacing must be:

- **Compact** — Maximize information density
- **Consistent** — Use the 4px base scale
- **Purposeful** — Every pixel serves a function
- **Responsive** — Adapt to screen size

**Avoid:** Giant padding, huge empty spaces, wasted vertical space

---

## Base Spacing Scale

### 4px Base Unit

All spacing values are multiples of 4px:

| Token | Value | Usage |
|-------|-------|-------|
| `0` | 0px | No spacing |
| `1` | 4px | Tight spacing, related elements |
| `2` | 8px | Default spacing, compact layouts |
| `3` | 12px | Moderate spacing, grouped elements |
| `4` | 16px | Standard spacing, sections |
| `5` | 20px | Loose spacing, major sections |
| `6` | 24px | Section separation |
| `8` | 32px | Major section separation |
| `10` | 40px | Page-level spacing |
| `12` | 48px | Large section gaps |
| `16` | 64px | Page margins |

### Tailwind Configuration

```javascript
spacing: {
  0: '0px',
  1: '4px',
  2: '8px',
  3: '12px',
  4: '16px',
  5: '20px',
  6: '24px',
  8: '32px',
  10: '40px',
  12: '48px',
  16: '64px',
}
```

---

## Spacing Tokens

### CSS Variables

```css
:root {
  /* Spacing scale */
  --spacing-0: 0px;
  --spacing-1: 4px;
  --spacing-2: 8px;
  --spacing-3: 12px;
  --spacing-4: 16px;
  --spacing-5: 20px;
  --spacing-6: 24px;
  --spacing-8: 32px;
  --spacing-10: 40px;
  --spacing-12: 48px;
  --spacing-16: 64px;

  /* Component-specific spacing */
  --spacing-card-padding: 16px;
  --spacing-button-padding-x: 16px;
  --spacing-button-padding-y: 8px;
  --spacing-input-padding-x: 12px;
  --spacing-input-padding-y: 8px;
  --spacing-section-gap: 24px;
}
```

---

## Component Spacing

### Cards

**Padding:** 16px (`spacing-4`)
**Gap between cards:** 16px (`spacing-4`)

```html
<div className="p-4 gap-4">
  <Card>Content</Card>
  <Card>Content</Card>
</div>
```

### Buttons

**Padding:** 8px vertical, 16px horizontal (`py-2 px-4`)
**Gap between buttons:** 8px (`gap-2`)

```html
<div className="flex gap-2">
  <button className="py-2 px-4">Primary</button>
  <button className="py-2 px-4">Secondary</button>
</div>
```

### Form Inputs

**Padding:** 8px vertical, 12px horizontal (`py-2 px-3`)
**Gap between fields:** 16px (`gap-4`)

```html
<div className="space-y-4">
  <input className="py-2 px-3" placeholder="Email" />
  <input className="py-2 px-3" placeholder="Password" />
</div>
```

### Tables

**Cell padding:** 12px vertical, 16px horizontal (`py-3 px-4`)
**Gap between tables:** 24px (`gap-6`)

```html
<table className="w-full">
  <thead>
    <tr className="py-3 px-4">
      <th>Header</th>
    </tr>
  </thead>
  <tbody>
    <tr className="py-3 px-4">
      <td>Data</td>
    </tr>
  </tbody>
</table>
```

### Modals

**Padding:** 24px (`p-6`)
**Gap between sections:** 16px (`gap-4`)

```html
<div className="p-6 space-y-4">
  <h2>Title</h2>
  <p>Content</p>
  <div className="flex gap-2">
    <button>Action</button>
  </div>
</div>
```

---

## Layout Spacing

### Page Structure

**Header to content:** 24px (`mt-6`)
**Section to section:** 32px (`space-y-8`)
**Page margins:** 24px on desktop, 16px on mobile

```html
<div className="p-6 md:p-6 space-y-8">
  <header>Header</header>
  <section>Content</section>
  <section>Content</section>
</div>
```

### Dashboard Layout

**Widget gap:** 16px (`gap-4`)
**Section gap:** 24px (`gap-6`)
**Dashboard padding:** 24px (`p-6`)

```html
<div className="p-6 space-y-6">
  <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
    <Widget>Stat 1</Widget>
    <Widget>Stat 2</Widget>
    <Widget>Stat 3</Widget>
  </div>
  <section>Charts</section>
</div>
```

---

## Responsive Spacing

### Mobile-First Approach

**Base spacing:** Compact (mobile)
**Desktop spacing:** Increased (tablet+)

### Responsive Pattern

```html
<!-- Mobile: 16px padding, Desktop: 24px padding -->
<div className="p-4 md:p-6">
  Content
</div>

<!-- Mobile: 8px gap, Desktop: 16px gap -->
<div className="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
  <Card>Item 1</Card>
  <Card>Item 2</Card>
</div>
```

### Breakpoint Guidelines

| Breakpoint | Spacing Adjustment |
|------------|-------------------|
| Mobile (< 768px) | Base spacing (compact) |
| Tablet (768px - 1024px) | +1 step (moderate) |
| Desktop (1024px+) | +2 steps (standard) |

---

## Spacing Rules

### Vertical Spacing

- **Between paragraphs:** 16px (`mb-4`)
- **Between list items:** 8px (`mb-2`)
- **Between sections:** 32px (`mb-8`)
- **Between form groups:** 16px (`mb-4`)

### Horizontal Spacing

- **Between inline elements:** 8px (`mx-2`)
- **Between buttons:** 8px (`gap-2`)
- **Between columns:** 16px (`gap-4`)
- **Page margins:** 24px (`px-6`)

### Padding

- **Cards:** 16px (`p-4`)
- **Modals:** 24px (`p-6`)
- **Inputs:** 8px vertical, 12px horizontal (`py-2 px-3`)
- **Buttons:** 8px vertical, 16px horizontal (`py-2 px-4`)

---

## Common Spacing Patterns

### Card with Header

```html
<div className="p-4 space-y-4">
  <div className="flex items-center gap-2">
    <h3 className="text-lg font-medium">Title</h3>
  </div>
  <p className="text-sm text-text-secondary">Content</p>
</div>
```

### Form Section

```html
<div className="space-y-4">
  <div>
    <label className="block mb-2 text-sm font-medium">Label</label>
    <input className="w-full py-2 px-3" />
  </div>
  <div>
    <label className="block mb-2 text-sm font-medium">Label</label>
    <input className="w-full py-2 px-3" />
  </div>
</div>
```

### Action Bar

```html
<div className="flex items-center justify-between gap-4 p-4">
  <div className="text-sm">Info</div>
  <div className="flex gap-2">
    <button className="py-2 px-4">Cancel</button>
    <button className="py-2 px-4">Submit</button>
  </div>
</div>
```

---

## Spacing Anti-Patterns

### ❌ Forbidden

```html
<!-- Hardcoded spacing -->
<div style="padding: 23px;">Content</div>

<!-- Inconsistent spacing -->
<div className="p-5 mb-7">Content</div>

<!-- Excessive spacing -->
<div className="p-16 space-y-20">Content</div>

<!-- Magic numbers -->
<div className="gap-[13px]">Content</div>
```

### ✅ Required

```html
<!-- Design tokens -->
<div className="p-4 space-y-4">Content</div>

<!-- Consistent spacing -->
<div className="p-4 mb-4">Content</div>

<!-- Appropriate spacing -->
<div className="p-4 space-y-4">Content</div>

<!-- Token-based spacing -->
<div className="gap-4">Content</div>
```

---

## Enforcement Rules

### Before Setting Spacing

1. **Is this a token value?** Use spacing scale (multiples of 4px)
2. **Is this consistent?** Use same spacing for similar elements
3. **Is this responsive?** Adjust for mobile/desktop
4. **Is this necessary?** Don't add spacing without purpose

### Spacing Checklist

- ✅ Spacing values from the 4px scale
- ✅ Design tokens used (no hardcoded values)
- ✅ Consistent spacing across similar components
- ✅ Responsive spacing applied
- ✅ No excessive or unnecessary spacing
- ✅ Compact layout maintained

---

## Tailwind Spacing Utilities

### Common Classes

```html
<!-- Padding -->
<div className="p-4">All sides</div>
<div className="px-4 py-2">Horizontal and vertical</div>
<div className="pt-4 pr-4 pb-4 pl-4">Individual sides</div>

<!-- Margin -->
<div className="m-4">All sides</div>
<div className="mx-auto">Horizontal center</div>
<div className="mt-4">Top only</div>

<!-- Gap -->
<div className="gap-4">Flex/Grid gap</div>
<div className="gap-x-4 gap-y-2">Individual gaps</div>

<!-- Space Between -->
<div className="space-y-4">Vertical spacing between children</div>
<div className="space-x-2">Horizontal spacing between children</div>
```

---

## References

- Design Principles: `design-principles.md`
- Color System: `color-system.md`
- Typography: `typography.md`
- Components: `components.md`
- Responsive Rules: `responsive-rules.md`
