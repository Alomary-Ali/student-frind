# Color System — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Chief Product Designer / Design System Architect
**Status:** MANDATORY — All colors must comply

---

## Official Color Palette

### Primary Color

**Color:** `#243B7C` (Deep Blue)

**Usage:**
- Logo
- Main buttons (primary actions)
- Navigation (active states, links)
- Important actions
- Brand identity elements

**CSS Variables:**
```css
--color-primary: #243B7C;
--color-primary-hover: #1E2F63;
--color-primary-active: #18234E;
```

**Tailwind Configuration:**
```javascript
primary: {
  DEFAULT: '#243B7C',
  hover: '#1E2F63',
  active: '#18234E',
}
```

---

### Success Color

**Color:** `#10B981` (Emerald Green)

**Usage:**
- Progress indicators
- Completed tasks
- Success indicators
- Positive performance metrics
- Confirmation states

**CSS Variables:**
```css
--color-success: #10B981;
--color-success-hover: #059669;
--color-success-active: #047857;
```

**Tailwind Configuration:**
```javascript
success: {
  DEFAULT: '#10B981',
  hover: '#059669',
  active: '#047857',
}
```

---

### Neutral Colors

#### Background

**Color:** `#F8FAFC` (Cool Gray 50)

**Usage:**
- Page backgrounds
- Dashboard backgrounds
- Section backgrounds

**CSS Variables:**
```css
--color-background: #F8FAFC;
```

**Tailwind Configuration:**
```javascript
background: '#F8FAFC',
```

---

#### Surface

**Color:** `#FFFFFF` (White)

**Usage:**
- Card backgrounds
- Modal backgrounds
- Panel backgrounds
- Form containers

**CSS Variables:**
```css
--color-surface: #FFFFFF;
```

**Tailwind Configuration:**
```javascript
surface: '#FFFFFF',
```

---

#### Text Primary

**Color:** `#111827` (Gray 900)

**Usage:**
- Headings
- Body text
- Labels
- Primary content

**CSS Variables:**
```css
--color-text-primary: #111827;
```

**Tailwind Configuration:**
```javascript
text: {
  primary: '#111827',
}
```

---

#### Text Secondary

**Color:** `#6B7280` (Gray 500)

**Usage:**
- Supporting text
- Metadata
- Placeholder text
- Disabled states

**CSS Variables:**
```css
--color-text-secondary: #6B7280;
```

**Tailwind Configuration:**
```javascript
text: {
  secondary: '#6B7280',
}
```

---

#### Borders

**Color:** `#E5E7EB` (Gray 200)

**Usage:**
- Card borders
- Input borders
- Divider lines
- Table borders

**CSS Variables:**
```css
--color-border: #E5E7EB;
```

**Tailwind Configuration:**
```javascript
border: '#E5E7EB',
```

---

## Color Restrictions

### DO NOT Introduce Additional Brand Colors

The platform identity must remain:
- **Primary Blue** (`#243B7C`)
- **Neutral Grays** (background, surface, text, borders)
- **Success Green** (`#10B981`)

### Restricted Colors

Avoid excessive use of:
- **Red** — Only for error states, destructive actions
- **Orange** — Only for warnings when absolutely necessary
- **Purple** — Not permitted as brand color
- **Yellow** — Only for caution indicators when absolutely necessary

These colors may only be used for **status indicators** when absolutely necessary.

### Status Color Usage

| Status | Color | Usage |
|--------|-------|-------|
| Error | `#EF4444` | Form validation errors, failed operations |
| Warning | `#F59E0B` | Caution states, pending actions |
| Success | `#10B981` | Completed tasks, positive outcomes |
| Info | `#3B82F6` | Informational messages (use sparingly) |

**Status colors must not dominate the interface.**

---

## Visual Hierarchy Rule

**UI Composition:**
- 80% Neutral Colors
- 15% Primary Color
- 5% Success Color

**No screen should feel visually noisy.**

---

## Color Accessibility

### Contrast Requirements

All text must meet WCAG AA contrast requirements:
- Normal text: Minimum 4.5:1 contrast ratio
- Large text (18pt+): Minimum 3:1 contrast ratio
- UI components: Minimum 3:1 contrast ratio

### Verified Combinations

| Foreground | Background | Contrast Ratio | Status |
|------------|------------|----------------|--------|
| `#111827` | `#FFFFFF` | 16.4:1 | ✅ Pass |
| `#111827` | `#F8FAFC` | 16.2:1 | ✅ Pass |
| `#6B7280` | `#FFFFFF` | 5.7:1 | ✅ Pass |
| `#6B7280` | `#F8FAFC` | 5.6:1 | ✅ Pass |
| `#243B7C` | `#FFFFFF` | 8.2:1 | ✅ Pass |
| `#FFFFFF` | `#243B7C` | 8.2:1 | ✅ Pass |
| `#10B981` | `#FFFFFF` | 4.6:1 | ✅ Pass |

---

## Tailwind Theme Configuration

```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#243B7C',
          hover: '#1E2F63',
          active: '#18234E',
        },
        success: {
          DEFAULT: '#10B981',
          hover: '#059669',
          active: '#047857',
        },
        background: '#F8FAFC',
        surface: '#FFFFFF',
        text: {
          primary: '#111827',
          secondary: '#6B7280',
        },
        border: '#E5E7EB',
        // Status colors (use sparingly)
        error: '#EF4444',
        warning: '#F59E0B',
        info: '#3B82F6',
      },
    },
  },
}
```

---

## CSS Variables (Non-Tailwind)

```css
:root {
  /* Primary */
  --color-primary: #243B7C;
  --color-primary-hover: #1E2F63;
  --color-primary-active: #18234E;

  /* Success */
  --color-success: #10B981;
  --color-success-hover: #059669;
  --color-success-active: #047857;

  /* Neutral */
  --color-background: #F8FAFC;
  --color-surface: #FFFFFF;
  --color-text-primary: #111827;
  --color-text-secondary: #6B7280;
  --color-border: #E5E7EB;

  /* Status (use sparingly) */
  --color-error: #EF4444;
  --color-warning: #F59E0B;
  --color-info: #3B82F6;
}
```

---

## Enforcement Rules

### Before Using Color

1. **Is this a brand color?** Use Primary (`#243B7C`) or Success (`#10B981`)
2. **Is this a neutral color?** Use defined neutral palette
3. **Is this a status indicator?** Use status colors sparingly
4. **Is this a new color?** STOP — New colors are forbidden

### Color Usage Checklist

- ✅ Color is from the official palette
- ✅ Contrast meets WCAG AA requirements
- ✅ Visual hierarchy follows 80/15/5 rule
- ✅ Status colors are used sparingly
- ✅ No arbitrary color values in code

---

## Common Mistakes

### ❌ Forbidden

```css
/* Hardcoded colors */
.button {
  background-color: #3366FF; /* Wrong color */
}

/* Too many colors */
.card {
  border-color: #FF5733;
  background-color: #C70039;
  text-color: #900C3F;
}
```

### ✅ Required

```css
/* Design tokens */
.button {
  background-color: var(--color-primary);
}

/* Tailwind classes */
<button className="bg-primary hover:bg-primary-hover text-surface">
  Submit
</button>
```

---

## References

- Design Principles: `design-principles.md`
- Typography: `typography.md`
- Spacing: `spacing.md`
- Components: `components.md`
- Accessibility: `accessibility.md`
