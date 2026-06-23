<?php

declare(strict_types=1);

namespace Modules\UI\Domain;

/**
 * DesignTokens — central registry of all design token CSS variable names.
 *
 * Every visual constant in the UI layer must be referenced here so that
 * Blade components, controllers, and tests can refer to tokens by a
 * stable, IDE-friendly constant rather than a raw string.
 *
 * Usage in Blade:
 *   <div style="color: var({{ Modules\UI\Domain\DesignTokens::COLOR_PRIMARY }})">
 *
 * All values map to CSS custom properties defined in tokens.css.
 */
final class DesignTokens
{
    /* ── Brand Palette ──────────────────────────────────────────── */
    public const COLOR_PRIMARY = '--color-primary';
    public const COLOR_PRIMARY_HOVER = '--color-primary-hover';
    public const COLOR_PRIMARY_ACTIVE = '--color-primary-active';
    public const COLOR_PRIMARY_LIGHT = '--color-primary-light';

    public const COLOR_NAVY = '--color-navy';
    public const COLOR_NAVY_HOVER = '--color-navy-hover';

    public const COLOR_ACCENT = '--color-accent';
    public const COLOR_ACCENT_HOVER = '--color-accent-hover';
    public const COLOR_ACCENT_ACTIVE = '--color-accent-active';
    public const COLOR_ACCENT_LIGHT = '--color-accent-light';

    /* ── Semantic ───────────────────────────────────────────────── */
    public const COLOR_WARNING = '--color-warning';
    public const COLOR_WARNING_HOVER = '--color-warning-hover';
    public const COLOR_WARNING_LIGHT = '--color-warning-light';

    public const COLOR_ERROR = '--color-error';
    public const COLOR_ERROR_HOVER = '--color-error-hover';
    public const COLOR_ERROR_LIGHT = '--color-error-light';

    public const COLOR_INFO = '--color-info';
    public const COLOR_INFO_LIGHT = '--color-info-light';

    public const COLOR_SUCCESS = '--color-success';
    public const COLOR_SUCCESS_LIGHT = '--color-success-light';

    /* ── Surface / Background ───────────────────────────────────── */
    public const COLOR_BACKGROUND = '--color-background';
    public const COLOR_SURFACE = '--color-surface';
    public const COLOR_SURFACE_RAISED = '--color-surface-raised';
    public const COLOR_SURFACE_HOVER = '--color-surface-hover';

    /* ── Text ───────────────────────────────────────────────────── */
    public const COLOR_TEXT_PRIMARY = '--color-text-primary';
    public const COLOR_TEXT_SECONDARY = '--color-text-secondary';
    public const COLOR_TEXT_MUTED = '--color-text-muted';
    public const COLOR_TEXT_ON_PRIMARY = '--color-text-on-primary';
    public const COLOR_TEXT_ON_NAVY = '--color-text-on-navy';

    /* ── Border ─────────────────────────────────────────────────── */
    public const COLOR_BORDER = '--color-border';
    public const COLOR_BORDER_LIGHT = '--color-border-light';

    /* ── Radius ─────────────────────────────────────────────────── */
    public const RADIUS_SM = '--radius-sm';
    public const RADIUS_MD = '--radius-md';
    public const RADIUS_LG = '--radius-lg';
    public const RADIUS_XL = '--radius-xl';
    public const RADIUS_2XL = '--radius-2xl';
    public const RADIUS_3XL = '--radius-3xl';
    public const RADIUS_FULL = '--radius-full';

    /* ── Shadow ─────────────────────────────────────────────────── */
    public const SHADOW_XS = '--shadow-xs';
    public const SHADOW_SM = '--shadow-sm';
    public const SHADOW_MD = '--shadow-md';
    public const SHADOW_LG = '--shadow-lg';
    public const SHADOW_XL = '--shadow-xl';
    public const SHADOW_ACCENT = '--shadow-accent';
    public const SHADOW_NAVY = '--shadow-navy';

    /* ── Typography ─────────────────────────────────────────────── */
    public const FONT_SANS = '--font-sans';
    public const FONT_SERIF = '--font-serif';

    public const TEXT_CAPTION = '--text-caption';
    public const TEXT_BODY = '--text-body';
    public const TEXT_BODY_LG = '--text-body-lg';
    public const TEXT_SUBTITLE = '--text-subtitle';
    public const TEXT_TITLE = '--text-title';
    public const TEXT_DISPLAY = '--text-display';

    /* ── Spacing ────────────────────────────────────────────────── */
    public const SPACING_0 = '--spacing-0';
    public const SPACING_1 = '--spacing-1';
    public const SPACING_2 = '--spacing-2';
    public const SPACING_3 = '--spacing-3';
    public const SPACING_4 = '--spacing-4';
    public const SPACING_5 = '--spacing-5';
    public const SPACING_6 = '--spacing-6';
    public const SPACING_8 = '--spacing-8';
    public const SPACING_10 = '--spacing-10';
    public const SPACING_12 = '--spacing-12';
    public const SPACING_16 = '--spacing-16';
}
