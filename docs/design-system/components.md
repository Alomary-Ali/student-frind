# Components — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Chief Product Designer / Design System Architect
**Status:** MANDATORY — All components must comply

---

## Component Philosophy

All components must be:

- **Reusable** — Single responsibility, composable
- **Token-based** — Use design tokens, no hardcoded values
- **Responsive** — Mobile-first, adapt to all screen sizes
- **Accessible** — WCAG AA compliant by default
- **Consistent** — Unified visual language

---

## Design Token System

### Token Categories

| Category | Purpose | Example |
|----------|---------|---------|
| Color | All color values | `--color-primary` |
| Typography | Font sizes, weights, line heights | `--font-size-base` |
| Spacing | All spacing values | `--spacing-4` |
| Radius | Border radius values | `--radius-md` |
| Shadow | Box shadow values | `--shadow-sm` |
| Animation | Duration, easing | `--transition-fast` |

### Token Usage Rule

**Hardcoded values are forbidden.**

```css
/* ❌ Forbidden */
.button {
  padding: 8px 16px;
  background-color: #243B7C;
  border-radius: 4px;
}

/* ✅ Required */
.button {
  padding: var(--spacing-2) var(--spacing-4);
  background-color: var(--color-primary);
  border-radius: var(--radius-md);
}
```

---

## Core Components

### Button

#### Variants

| Variant | Usage | Style |
|---------|-------|-------|
| Primary | Main actions | Primary background, white text |
| Secondary | Secondary actions | White background, primary border |
| Ghost | Tertiary actions | Transparent background, primary text |
| Destructive | Destructive actions | Error background, white text |

#### Sizes

| Size | Padding | Font Size |
|------|---------|-----------|
| sm | 6px 12px | 14px |
| md | 8px 16px | 14px |
| lg | 12px 20px | 16px |

#### States

- **Default:** Base styles
- **Hover:** Darker background (10% darker)
- **Active:** Even darker background (20% darker)
- **Disabled:** 50% opacity, no pointer events

#### Example

```html
<button className="bg-primary hover:bg-primary-hover text-surface py-2 px-4 rounded-md transition-colors">
  Submit
</button>
```

---

### Input

#### Variants

| Variant | Usage |
|---------|-------|
| Default | Standard text input |
| Error | Validation error state |
| Disabled | Disabled state |

#### States

- **Default:** Border color, white background
- **Focus:** Primary border color, ring
- **Error:** Error border color, error message
- **Disabled:** Gray background, no interaction

#### Example

```html
<input
  type="text"
  className="w-full py-2 px-3 border-border border rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
  placeholder="Enter text"
/>
```

---

### Card

#### Structure

```
┌─────────────────────────┐
│ Header (optional)       │
├─────────────────────────┤
│ Content                 │
│ (flexible)              │
├─────────────────────────┤
│ Footer (optional)       │
└─────────────────────────┘
```

#### Spacing

- **Padding:** 16px (`p-4`)
- **Gap between sections:** 16px (`gap-4`)
- **Border radius:** 8px (`rounded-lg`)

#### Example

```html
<div className="bg-surface border-border border rounded-lg p-4 space-y-4">
  <div className="flex items-center justify-between">
    <h3 className="text-lg font-medium text-text-primary">Title</h3>
  </div>
  <p className="text-sm text-text-secondary">Content</p>
</div>
```

---

### Modal

#### Structure

```
┌─────────────────────────┐
│ Header                  │
│ (title + close button)  │
├─────────────────────────┤
│ Body                    │
│ (scrollable content)    │
├─────────────────────────┤
│ Footer                  │
│ (actions)               │
└─────────────────────────┘
```

#### Spacing

- **Padding:** 24px (`p-6`)
- **Gap between sections:** 16px (`gap-4`)
- **Max width:** 600px
- **Border radius:** 12px (`rounded-xl`)

#### Example

```html
<div className="bg-surface rounded-xl p-6 max-w-lg">
  <div className="flex items-center justify-between mb-4">
    <h2 className="text-xl font-semibold text-text-primary">Title</h2>
    <button className="text-text-secondary hover:text-text-primary">✕</button>
  </div>
  <div className="mb-4">
    <p className="text-sm text-text-secondary">Content</p>
  </div>
  <div className="flex justify-end gap-2">
    <button className="py-2 px-4">Cancel</button>
    <button className="bg-primary py-2 px-4 text-surface">Confirm</button>
  </div>
</div>
```

---

### Table

#### Structure

```
┌─────────────────────────┐
│ Header Row              │
├─────────────────────────┤
│ Data Row 1              │
│ Data Row 2              │
│ Data Row 3              │
└─────────────────────────┘
```

#### Spacing

- **Cell padding:** 12px vertical, 16px horizontal (`py-3 px-4`)
- **Border:** Bottom border on rows
- **Header background:** Light gray (`#F8FAFC`)

#### Responsive Behavior

- **Desktop:** Standard table
- **Mobile:** Transform to card view

#### Example

```html
<table className="w-full">
  <thead>
    <tr className="bg-background">
      <th className="py-3 px-4 text-left text-sm font-medium text-text-primary">Name</th>
      <th className="py-3 px-4 text-left text-sm font-medium text-text-primary">Status</th>
    </tr>
  </thead>
  <tbody>
    <tr className="border-border border-b">
      <td className="py-3 px-4 text-sm text-text-primary">Item 1</td>
      <td className="py-3 px-4 text-sm text-text-secondary">Active</td>
    </tr>
  </tbody>
</table>
```

---

### Badge

#### Variants

| Variant | Usage | Color |
|---------|-------|-------|
| Default | General status | Primary background |
| Success | Positive status | Success background |
| Error | Negative status | Error background |
| Warning | Caution status | Warning background |

#### Sizes

| Size | Padding | Font Size |
|------|---------|-----------|
| sm | 2px 8px | 12px |
| md | 4px 12px | 14px |

#### Example

```html
<span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-success text-surface">
  Active
</span>
```

---

### Alert

#### Variants

| Variant | Usage | Color |
|---------|-------|-------|
| Info | Informational | Primary |
| Success | Success message | Success |
| Warning | Warning message | Warning |
| Error | Error message | Error |

#### Structure

```
┌─────────────────────────┐
│ [Icon] Message          │
│ [Close button]          │
└─────────────────────────┘
```

#### Example

```html
<div className="flex items-center p-4 bg-success/10 border-success border rounded-lg">
  <span className="text-success text-sm">Success message</span>
</div>
```

---

## Component Rules

### Naming Convention

Components must use descriptive names:

- ✅ `StudentCard`, `CourseTable`, `GpaChart`
- ❌ `Card`, `Table`, `Chart` (too generic)

### Prop Interfaces

TypeScript interfaces must be explicit:

```typescript
interface StudentCardProps {
  student: Student;
  onEdit?: (id: string) => void;
  onDelete?: (id: string) => void;
}
```

### Component Size

- **Maximum lines:** 300 lines
- **Maximum props:** 10 props
- **If exceeded:** Extract sub-components

---

## Responsive Components

### Mobile-First Approach

All components must work on mobile first:

```html
<!-- Mobile: stacked, Desktop: side-by-side -->
<div className="flex flex-col md:flex-row gap-4">
  <div>Column 1</div>
  <div>Column 2</div>
</div>
```

### Responsive Patterns

| Pattern | Mobile | Desktop |
|---------|--------|---------|
| Tables | Card view | Standard table |
| Forms | Stacked | Multi-column |
| Navigation | Bottom bar | Top bar |
| Cards | Full width | Grid layout |

---

## Accessibility Requirements

### Keyboard Navigation

All interactive components must be keyboard accessible:

- Tab order must be logical
- Focus states must be visible
- Escape key closes modals
- Enter/Space activates buttons

### Screen Reader Support

- Use semantic HTML (`<button>`, `<input>`, etc.)
- Provide ARIA labels when necessary
- Announce state changes
- Use proper heading hierarchy

### Focus Management

- Focus visible states: `ring-2 ring-primary`
- Focus trap in modals
- Focus restoration after close

---

## Component Library Structure

```
src/
├── components/
│   ├── ui/
│   │   ├── Button.tsx
│   │   ├── Input.tsx
│   │   ├── Card.tsx
│   │   ├── Modal.tsx
│   │   ├── Table.tsx
│   │   ├── Badge.tsx
│   │   └── Alert.tsx
│   ├── domain/
│   │   ├── StudentCard.tsx
│   │   ├── CourseTable.tsx
│   │   └── GpaChart.tsx
│   └── layouts/
│       ├── Header.tsx
│       ├── Sidebar.tsx
│       └── Footer.tsx
```

---

## Enforcement Rules

### Before Creating Components

1. **Is this reusable?** If used once, consider inline
2. **Are design tokens used?** No hardcoded values
3. **Is it responsive?** Mobile-first approach
4. **Is it accessible?** WCAG AA compliant
5. **Is the name descriptive?** Clear, specific naming

### Component Checklist

- ✅ Design tokens used (no hardcoded values)
- ✅ Responsive design implemented
- ✅ Accessible by default (keyboard, screen reader)
- ✅ Descriptive component name
- ✅ Proper TypeScript interfaces
- ✅ Within size limits (300 lines max)
- ✅ Single responsibility principle

---

## References

- Design Principles: `design-principles.md`
- Color System: `color-system.md`
- Typography: `typography.md`
- Spacing: `spacing.md`
- Responsive Rules: `responsive-rules.md`
- Accessibility: `accessibility.md`
