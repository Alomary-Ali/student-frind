<?php

declare(strict_types=1);

namespace Modules\UI\Tests\Unit;

use Modules\UI\Domain\DesignTokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DesignTokensTest extends TestCase
{
    #[Test]
    public function all_colors_have_expected_constants(): void
    {
        $this->assertSame('--color-primary', DesignTokens::COLOR_PRIMARY);
        $this->assertSame('--color-primary-hover', DesignTokens::COLOR_PRIMARY_HOVER);
        $this->assertSame('--color-primary-active', DesignTokens::COLOR_PRIMARY_ACTIVE);
        $this->assertSame('--color-primary-light', DesignTokens::COLOR_PRIMARY_LIGHT);
        $this->assertSame('--color-navy', DesignTokens::COLOR_NAVY);
        $this->assertSame('--color-accent', DesignTokens::COLOR_ACCENT);
        $this->assertSame('--color-warning', DesignTokens::COLOR_WARNING);
        $this->assertSame('--color-error', DesignTokens::COLOR_ERROR);
        $this->assertSame('--color-info', DesignTokens::COLOR_INFO);
        $this->assertSame('--color-background', DesignTokens::COLOR_BACKGROUND);
        $this->assertSame('--color-surface', DesignTokens::COLOR_SURFACE);
        $this->assertSame('--color-text-primary', DesignTokens::COLOR_TEXT_PRIMARY);
        $this->assertSame('--color-text-secondary', DesignTokens::COLOR_TEXT_SECONDARY);
        $this->assertSame('--color-text-muted', DesignTokens::COLOR_TEXT_MUTED);
        $this->assertSame('--color-border', DesignTokens::COLOR_BORDER);
    }

    #[Test]
    public function all_radii_have_expected_constants(): void
    {
        $this->assertSame('--radius-sm', DesignTokens::RADIUS_SM);
        $this->assertSame('--radius-md', DesignTokens::RADIUS_MD);
        $this->assertSame('--radius-lg', DesignTokens::RADIUS_LG);
        $this->assertSame('--radius-xl', DesignTokens::RADIUS_XL);
        $this->assertSame('--radius-2xl', DesignTokens::RADIUS_2XL);
        $this->assertSame('--radius-3xl', DesignTokens::RADIUS_3XL);
        $this->assertSame('--radius-full', DesignTokens::RADIUS_FULL);
    }

    #[Test]
    public function all_shadows_have_expected_constants(): void
    {
        $this->assertSame('--shadow-xs', DesignTokens::SHADOW_XS);
        $this->assertSame('--shadow-sm', DesignTokens::SHADOW_SM);
        $this->assertSame('--shadow-md', DesignTokens::SHADOW_MD);
        $this->assertSame('--shadow-lg', DesignTokens::SHADOW_LG);
        $this->assertSame('--shadow-xl', DesignTokens::SHADOW_XL);
        $this->assertSame('--shadow-accent', DesignTokens::SHADOW_ACCENT);
        $this->assertSame('--shadow-navy', DesignTokens::SHADOW_NAVY);
    }

    #[Test]
    public function all_spacing_tokens_have_expected_constants(): void
    {
        $this->assertSame('--spacing-0', DesignTokens::SPACING_0);
        $this->assertSame('--spacing-4', DesignTokens::SPACING_4);
        $this->assertSame('--spacing-8', DesignTokens::SPACING_8);
        $this->assertSame('--spacing-16', DesignTokens::SPACING_16);
    }

    #[Test]
    public function all_constants_have_values(): void
    {
        $reflection = new \ReflectionClass(DesignTokens::class);
        foreach ($reflection->getConstants() as $name => $value) {
            $this->assertNotEmpty($value, "Constant {$name} must not be empty");
        }
    }
}
