# Typography — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Chief Product Designer / Design System Architect
**Status:** MANDATORY — All typography must comply

---

## Typeface Selection

### Primary Typeface

**Font Family:** Inter (or system-ui fallback)

**Rationale:**
- Optimized for screen readability
- Excellent legibility at small sizes
- Professional, modern appearance
- Supports multiple weights
- Free and open-source

**Font Stack:**
```css
font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
```

---

## Font Weights

| Weight | Name | Usage |
|--------|------|-------|
| 400 | Regular | Body text, labels |
| 500 | Medium | Emphasized text, buttons |
| 600 | Semibold | Headings, important labels |
| 700 | Bold | Page titles, strong emphasis |

**Do not use weight 300 (Light) or 800+ (Extra Bold).**

---

## Font Sizes

### Scale

| Token | Size | Line Height | Usage |
|-------|------|-------------|-------|
| `xs` | 12px | 16px | Captions, metadata |
| `sm` | 14px | 20px | Secondary text, labels |
| `base` | 16px | 24px | Body text, default |
| `lg` | 18px | 28px | Large body, emphasized text |
| `xl` | 20px | 28px | Small headings |
| `2xl` | 24px | 32px | Section headings |
| `3xl` | 30px | 36px | Page titles |
| `4xl` | 36px | 44px | Hero titles (rare) |

### Tailwind Configuration

```javascript
fontSize: {
  xs: ['12px', { lineHeight: '16px' }],
  sm: ['14px', { lineHeight: '20px' }],
  base: ['16px', { lineHeight: '24px' }],
  lg: ['18px', { lineHeight: '28px' }],
  xl: ['20px', { lineHeight: '28px' }],
  '2xl': ['24px', { lineHeight: '32px' }],
  '3xl': ['30px', { lineHeight: '36px' }],
  '4xl': ['36px', { lineHeight: '44px' }],
}
```

---

## Typography Tokens

### CSS Variables

```css
:root {
  /* Font family */
  --font-family-base: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

  /* Font sizes */
  --font-size-xs: 12px;
  --font-size-sm: 14px;
  --font-size-base: 16px;
  --font-size-lg: 18px;
  --font-size-xl: 20px;
  --font-size-2xl: 24px;
  --font-size-3xl: 30px;
  --font-size-4xl: 36px;

  /* Line heights */
  --line-height-xs: 16px;
  --line-height-sm: 20px;
  --line-height-base: 24px;
  --line-height-lg: 28px;
  --line-height-xl: 28px;
  --line-height-2xl: 32px;
  --line-height-3xl: 36px;
  --line-height-4xl: 44px;

  /* Font weights */
  --font-weight-regular: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
}
```

---

## Heading Hierarchy

### H1 — Page Title

- **Size:** `3xl` (30px)
- **Weight:** Semibold (600)
- **Line Height:** 36px
- **Color:** Text Primary (`#111827`)
- **Usage:** Main page title, one per page

### H2 — Section Title

- **Size:** `2xl` (24px)
- **Weight:** Semibold (600)
- **Line Height:** 32px
- **Color:** Text Primary (`#111827`)
- **Usage:** Major section headings

### H3 — Subsection Title

- **Size:** `xl` (20px)
- **Weight:** Semibold (600)
- **Line Height:** 28px
- **Color:** Text Primary (`#111827`)
- **Usage:** Subsection headings within sections

### H4 — Component Title

- **Size:** `lg` (18px)
- **Weight:** Medium (500)
- **Line Height:** 28px
- **Color:** Text Primary (`#111827`)
- **Usage:** Card titles, panel headers

---

## Body Text

### Primary Body

- **Size:** `base` (16px)
- **Weight:** Regular (400)
- **Line Height:** 24px
- **Color:** Text Primary (`#111827`)
- **Usage:** Main content, paragraphs

### Secondary Body

- **Size:** `sm` (14px)
- **Weight:** Regular (400)
- **Line Height:** 20px
- **Color:** Text Secondary (`#6B7280`)
- **Usage:** Supporting text, descriptions, metadata

---

## Labels

### Form Labels

- **Size:** `sm` (14px)
- **Weight:** Medium (500)
- **Line Height:** 20px
- **Color:** Text Primary (`#111827`)
- **Usage:** Form field labels

### Data Labels

- **Size:** `xs` (12px)
- **Weight:** Medium (500)
- **Line Height:** 16px
- **Color:** Text Secondary (`#6B7280`)
- **Usage:** Table headers, metadata labels

---

## Links

### Inline Links

- **Size:** Inherits from parent
- **Weight:** Medium (500)
- **Color:** Primary (`#243B7C`)
- **Decoration:** Underline on hover only
- **Usage:** Links within body text

### Navigation Links

- **Size:** `sm` (14px)
- **Weight:** Medium (500)
- **Color:** Text Secondary (`#6B7280`)
- **Active Color:** Primary (`#243B7C`)
- **Usage:** Navigation menu items

---

## Text Alignment

### Default Alignment

- **Direction:** Left-to-right (LTR)
- **Alignment:** Left
- **Justification:** Never justify text (creates uneven spacing)

### Exceptions

- **Numbers:** Right-aligned in tables
- **Center:** Only for logos, icons, or decorative elements
- **Right:** Only for RTL language support

---

## Text Truncation

### Single Line Truncation

```css
.truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
```

### Multi-Line Truncation

```css
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
```

---

## Responsive Typography

### Mobile Adjustments

On screens smaller than 768px:
- Reduce heading sizes by one step (H1 becomes H2 size)
- Maintain line height ratios
- Never go below 14px for body text

### Tailwind Responsive Classes

```html
<h1 className="text-2xl md:text-3xl font-semibold text-text-primary">
  Page Title
</h1>
```

---

## Letter Spacing

### Default

- **Normal:** 0 (default)
- **Tracking-tight:** -0.025em (headings)
- **Tracking-wide:** 0.025em (rare, only for uppercase labels)

### Usage Rules

- **Headings:** Use `tracking-tight` for larger headings
- **Body:** Never adjust letter spacing
- **Uppercase:** Use `tracking-wide` for uppercase labels only

---

## Typography Best Practices

### ✅ Do

- Use semantic HTML (`<h1>`, `<h2>`, etc.)
- Maintain clear heading hierarchy
- Use appropriate line heights for readability
- Ensure contrast meets WCAG AA requirements
- Use design tokens, not hardcoded values

### ❌ Don't

- Skip heading levels (H1 → H3)
- Use headings for visual styling only
- Use all caps for body text
- Use multiple font families
- Hardcode font sizes or weights
- Use light weights (< 400)

---

## Tailwind Typography Plugin

If using `@tailwindcss/typography` plugin:

```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: theme('colors.text.primary'),
            a: {
              color: theme('colors.primary'),
              '&:hover': {
                color: theme('colors.primary.hover'),
              },
            },
            h1: {
              color: theme('colors.text.primary'),
              fontWeight: theme('fontWeight.semibold'),
            },
            h2: {
              color: theme('colors.text.primary'),
              fontWeight: theme('fontWeight.semibold'),
            },
            h3: {
              color: theme('colors.text.primary'),
              fontWeight: theme('fontWeight.semibold'),
            },
            strong: {
              color: theme('colors.text.primary'),
              fontWeight: theme('fontWeight.semibold'),
            },
          },
        },
      }),
    },
  },
}
```

---

## Enforcement Rules

### Before Using Typography

1. **Is this semantic?** Use appropriate HTML element
2. **Is the hierarchy clear?** Maintain heading levels
3. **Are design tokens used?** No hardcoded values
4. **Is contrast sufficient?** Verify WCAG AA compliance
5. **Is line height appropriate?** Ensure readability

### Typography Checklist

- ✅ Semantic HTML elements used
- ✅ Clear heading hierarchy maintained
- ✅ Design tokens used (no hardcoded values)
- ✅ Contrast meets WCAG AA requirements
- ✅ Appropriate line heights for readability
- ✅ Font weights within allowed range (400-700)

---

## References

- Design Principles: `design-principles.md`
- Color System: `color-system.md`
- Spacing: `spacing.md`
- Components: `components.md`
- Accessibility: `accessibility.md`
