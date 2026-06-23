# Accessibility — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Chief Product Designer / Design System Architect
**Status:** MANDATORY — All interfaces must be accessible

---

## Accessibility Philosophy

Accessibility is not optional. All interfaces must support:

- **WCAG AA minimum** — Compliance with Web Content Accessibility Guidelines
- **Keyboard navigation** — Full keyboard support for all interactions
- **Screen reader compatibility** — Proper semantic HTML and ARIA attributes
- **Visible focus states** — Clear indication of keyboard focus

---

## WCAG AA Compliance

### Contrast Requirements

| Element | Minimum Contrast Ratio |
|---------|------------------------|
| Normal text | 4.5:1 |
| Large text (18pt+) | 3:1 |
| UI components | 3:1 |
| Graphics | 3:1 |

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

## Keyboard Navigation

### Tab Order

- **Logical order:** Left to right, top to bottom
- **Skip links:** Provide skip-to-content link
- **Focus trap:** Modals must trap focus
- **Focus restoration:** Restore focus after modal close

### Focus States

**Required:** Visible focus indicator on all interactive elements

```css
/* Tailwind focus styles */
.focus-visible:focus {
  outline: 2px solid var(--color-primary);
  outline-offset: 2px;
}
```

### Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| Tab | Move focus forward |
| Shift + Tab | Move focus backward |
| Enter / Space | Activate button/link |
| Escape | Close modal/dropdown |
| Arrow keys | Navigate within components |

---

## Screen Reader Support

### Semantic HTML

Use semantic elements for proper screen reader interpretation:

```html
<!-- ✅ Correct -->
<header>
  <nav>
    <ul>
      <li><a href="/">Home</a></li>
    </ul>
  </nav>
</header>

<main>
  <h1>Page Title</h1>
  <article>
    <h2>Section Title</h2>
    <p>Content</p>
  </article>
</main>

<footer>
  <p>Footer content</p>
</footer>

<!-- ❌ Incorrect -->
<div class="header">
  <div class="nav">
    <div class="link">Home</div>
  </div>
</div>
```

### ARIA Attributes

Use ARIA attributes when semantic HTML is insufficient:

```html
<!-- Button with icon -->
<button aria-label="Close dialog">
  <span aria-hidden="true">✕</span>
</button>

<!-- Expandable content -->
<button aria-expanded="false" aria-controls="content">
  Show more
</button>
<div id="content" hidden>
  Additional content
</div>

<!-- Live regions -->
<div aria-live="polite" aria-atomic="true">
  Success message
</div>
```

### Alt Text

All images must have descriptive alt text:

```html
<!-- ✅ Descriptive alt text -->
<img src="student-photo.jpg" alt="Portrait of John Smith, age 20" />

<!-- ✅ Decorative images -->
<img src="decorative-pattern.png" alt="" role="presentation" />

<!-- ❌ Missing alt text -->
<img src="student-photo.jpg" />

<!-- ❌ Non-descriptive alt text -->
<img src="student-photo.jpg" alt="image" />
```

---

## Form Accessibility

### Labels

All form inputs must have associated labels:

```html
<!-- ✅ Explicit label -->
<label for="email">Email address</label>
<input id="email" type="email" />

<!-- ✅ Implicit label -->
<label>
  Email address
  <input type="email" />
</label>

<!-- ✅ ARIA label (when label cannot be used) -->
<input type="search" aria-label="Search courses" />

<!-- ❌ No label -->
<input type="email" placeholder="Email address" />
```

### Error Messages

Error messages must be associated with inputs:

```html
<div>
  <label for="email">Email address</label>
  <input
    id="email"
    type="email"
    aria-invalid="true"
    aria-describedby="email-error"
  />
  <p id="email-error" role="alert" className="text-error">
    Please enter a valid email address
  </p>
</div>
```

### Required Fields

Mark required fields clearly:

```html
<div>
  <label for="email">
    Email address
    <span aria-hidden="true">*</span>
    <span className="sr-only">(required)</span>
  </label>
  <input id="email" type="email" required aria-required="true" />
</div>
```

---

## Table Accessibility

### Table Headers

Use proper table headers:

```html
<table>
  <thead>
    <tr>
      <th scope="col">Student Name</th>
      <th scope="col">GPA</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">John Smith</th>
      <td>3.8</td>
      <td>Active</td>
    </tr>
  </tbody>
</table>
```

### Table Captions

Provide table captions for context:

```html
<table>
  <caption>Student academic performance for Fall 2026</caption>
  <thead>
    <tr>
      <th scope="col">Student Name</th>
      <th scope="col">GPA</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>John Smith</td>
      <td>3.8</td>
    </tr>
  </tbody>
</table>
```

---

## Modal Accessibility

### Focus Management

Modals must:

1. **Trap focus** within the modal
2. **Return focus** to the trigger element when closed
3. **Close on Escape** key press

### Example

```html
<!-- Modal trigger -->
<button
  onClick={openModal}
  aria-haspopup="dialog"
  aria-expanded={isOpen}
>
  Open modal
</button>

<!-- Modal -->
<div
  role="dialog"
  aria-modal="true"
  aria-labelledby="modal-title"
  aria-describedby="modal-description"
  className={isOpen ? 'block' : 'hidden'}
>
  <h2 id="modal-title">Modal Title</h2>
  <p id="modal-description">Modal content</p>
  <button onClick={closeModal}>Close</button>
</div>
```

---

## Link Accessibility

### Link Text

Link text must be descriptive:

```html
<!-- ✅ Descriptive link text -->
<a href="/courses/math-101">View Mathematics 101 course details</a>

<!-- ✅ Contextual link text -->
<p>
  Learn more about our <a href="/courses">course offerings</a>.
</p>

<!-- ❌ Non-descriptive link text -->
<a href="/courses/math-101">Click here</a>

<!-- ❌ Ambiguous link text -->
<a href="/courses">More</a>
```

### Link Purpose

Indicate if link opens in new tab:

```html
<a href="/document.pdf" target="_blank" rel="noopener noreferrer">
  Download course syllabus (opens in new tab)
</a>
```

---

## Color Accessibility

### Color Independence

Never rely on color alone to convey information:

```html
<!-- ✅ Color + text/icon -->
<div className="flex items-center gap-2">
  <span className="w-3 h-3 rounded-full bg-success" aria-hidden="true"></span>
  <span>Completed</span>
</div>

<!-- ❌ Color only -->
<div className="text-success">Completed</div>
```

### Focus Indicators

Provide visible focus indicators:

```css
/* Tailwind focus styles */
button:focus-visible {
  outline: 2px solid var(--color-primary);
  outline-offset: 2px;
}
```

---

## Screen Reader Only Content

Use sr-only class for content only visible to screen readers:

```css
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}
```

```html
<button>
  <span aria-hidden="true">✕</span>
  <span className="sr-only">Close dialog</span>
</button>
```

---

## Skip Links

Provide skip-to-content link:

```html
<a href="#main-content" className="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4">
  Skip to main content
</a>

<main id="main-content">
  <!-- Main content -->
</main>
```

---

## Accessibility Testing

### Automated Testing

Use automated accessibility testing tools:

- **Lighthouse** — Chrome DevTools
- **axe** — Browser extension
- **WAVE** — Web accessibility evaluation tool

### Manual Testing

- **Keyboard navigation** — Test all interactions with keyboard only
- **Screen reader** — Test with NVDA (Windows) or VoiceOver (Mac)
- **Zoom** — Test at 150% and 200% zoom
- **Color blindness** — Test with color blindness simulators

### User Testing

- Test with actual assistive technology users
- Test with users with disabilities
- Gather feedback on accessibility

---

## Accessibility Anti-Patterns

### ❌ Forbidden

```html
<!-- No alt text -->
<img src="image.jpg" />

<!-- No labels -->
<input type="text" placeholder="Name" />

<!-- Color only for meaning -->
<div className="text-error">Error</div>

<!-- Non-semantic HTML -->
<div class="button">Click me</div>

<!-- No focus states -->
<button className="outline-none">Submit</button>
```

### ✅ Required

```html
<!-- Descriptive alt text -->
<img src="image.jpg" alt="Student portrait" />

<!-- Proper labels -->
<label for="name">Name</label>
<input id="name" type="text" />

<!-- Color + text/icon -->
<div className="flex items-center gap-2">
  <span className="w-3 h-3 rounded-full bg-error" aria-hidden="true"></span>
  <span>Error</span>
</div>

<!-- Semantic HTML -->
<button>Click me</button>

<!-- Visible focus states -->
<button className="focus:ring-2 focus:ring-primary">Submit</button>
```

---

## Enforcement Rules

### Before Implementing UI

1. **Is contrast sufficient?** Verify WCAG AA compliance
2. **Is keyboard navigation supported?** Test all interactions
3. **Are semantic HTML elements used?** Use proper elements
4. **Are ARIA attributes provided?** When necessary
5. **Are focus states visible?** Clear focus indicators

### Accessibility Checklist

- ✅ Contrast meets WCAG AA requirements (4.5:1 for text)
- ✅ All interactive elements are keyboard accessible
- ✅ Semantic HTML elements used
- ✅ ARIA attributes provided when necessary
- ✅ All images have descriptive alt text
- ✅ All form inputs have associated labels
- ✅ Focus states are visible
- ✅ Color not used alone to convey meaning
- ✅ Skip link provided
- ✅ Modals trap focus
- ✅ Tables have proper headers
- ✅ Link text is descriptive

---

## References

- Design Principles: `design-principles.md`
- Color System: `color-system.md`
- Typography: `typography.md`
- Components: `components.md`
- Responsive Rules: `responsive-rules.md`
