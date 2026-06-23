#!/usr/bin/env php
<?php

/**
 * رفيق الطالب — Rulebook Validator
 * تحقق من سلامة الكود قبل أي Merge
 *
 * Usage: php scripts/validate-rulebook.php
 */

$rootDir = dirname(__DIR__);
$viewsDir = $rootDir . '/resources/views';
$srcDir   = $rootDir . '/src';
$errors   = [];
$warnings = [];
$passed   = 0;

echo "\n📘 رفيق الطالب — Rulebook Validator\n";
echo str_repeat('─', 50) . "\n\n";

// ─────────────────────────────────────────
// CHECK 1: No standalone HTML in views (§7)
// ─────────────────────────────────────────
echo "🔍 §7 — Checking for standalone HTML views...\n";

$viewFiles = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir)
);

foreach ($viewFiles as $file) {
    if ($file->getExtension() !== 'php') continue;

    // Skip the root layouts
    $relativePath = str_replace($viewsDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
    if (str_starts_with($relativePath, 'layouts')) continue;
    if (str_starts_with($relativePath, 'components')) continue;
    if (str_starts_with($relativePath, 'auth')) continue; // auth has own layout

    $content = file_get_contents($file->getPathname());

    if (str_contains($content, '<!DOCTYPE html>') || str_contains($content, '<html')) {
        $errors[] = "[§7] STANDALONE HTML: {$relativePath}";
    } else {
        $passed++;
    }

    if (!str_contains($content, "@extends('layouts.dashboard')") && !str_contains($content, '@extends("layouts.dashboard")')) {
        // Only warn for blade files that are page-level (not partials)
        if (!str_starts_with(basename($relativePath), '_')) {
            $warnings[] = "[§7] Missing @extends('layouts.dashboard'): {$relativePath}";
        }
    }
}

// ─────────────────────────────────────────
// CHECK 2: No mock data patterns in views (§11)
// ─────────────────────────────────────────
echo "🔍 §11 — Checking for mock/hardcoded data in views...\n";

$mockPatterns = [
    '/\@foreach\s*\(\s*\[/' => '@foreach with hardcoded array',
    '/\$data\s*=\s*\[/'     => 'Hardcoded $data array in Controller',
    '/fake\(\)/'            => 'Faker helper in production',
    '/Str::random\(/'       => 'Str::random() in production',
    '/rand\(\d/'            => 'rand() with hardcoded values',
];

foreach ($viewFiles as $file) {
    if ($file->getExtension() !== 'php') continue;

    $content      = file_get_contents($file->getPathname());
    $relativePath = str_replace($viewsDir . DIRECTORY_SEPARATOR, '', $file->getPathname());

    foreach ($mockPatterns as $pattern => $description) {
        if (@preg_match($pattern, $content)) {
            $warnings[] = "[§11] {$description}: {$relativePath}";
        }
    }
}

// ─────────────────────────────────────────
// CHECK 3: No provider SDKs in Domain/Application (§12)
// ─────────────────────────────────────────
echo "🔍 §12 — Checking for provider SDKs in Domain/Application...\n";

$forbiddenImports = [
    'use OpenAI\\',
    'use Mailgun\\',
    'use Stripe\\',
    'use Twilio\\',
    'use SendGrid\\',
    'use Google\\Cloud\\',
    'use Aws\\',
];

$domainAppDirs = [
    $srcDir . '/*/Domain',
    $srcDir . '/*/Application',
];

foreach ($domainAppDirs as $pattern) {
    $dirs = glob($pattern, GLOB_ONLYDIR);
    if (!$dirs) continue;

    foreach ($dirs as $dir) {
        $phpFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir)
        );

        foreach ($phpFiles as $file) {
            if ($file->getExtension() !== 'php') continue;

            $content      = file_get_contents($file->getPathname());
            $relativePath = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $file->getPathname());

            foreach ($forbiddenImports as $import) {
                if (str_contains($content, $import)) {
                    $errors[] = "[§12] Provider SDK in Domain/Application ({$import}): {$relativePath}";
                }
            }
        }
    }
}

// ─────────────────────────────────────────
// CHECK 4: strict_types in PHP files
// ─────────────────────────────────────────
echo "🔍 §3 — Checking declare(strict_types=1)...\n";

$phpDirs = [
    $rootDir . '/app',
    $srcDir,
];

foreach ($phpDirs as $dir) {
    if (!is_dir($dir)) continue;

    $phpFiles = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );

    foreach ($phpFiles as $file) {
        if ($file->getExtension() !== 'php') continue;

        $content = file_get_contents($file->getPathname());
        $relativePath = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $file->getPathname());

        // Skip config files and service providers bootstrapping
        if (str_contains($relativePath, 'config/')) continue;

        if (!str_contains($content, 'declare(strict_types=1)')) {
            $warnings[] = "[§3] Missing declare(strict_types=1): {$relativePath}";
        } else {
            $passed++;
        }
    }
}

// ─────────────────────────────────────────
// CHECK 5: No DB:: in Domain layer
// ─────────────────────────────────────────
echo "🔍 §3 — Checking for DB:: facade in Domain...\n";

$domainDirs = glob($srcDir . '/*/Domain', GLOB_ONLYDIR) ?: [];

foreach ($domainDirs as $dir) {
    $phpFiles = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );

    foreach ($phpFiles as $file) {
        if ($file->getExtension() !== 'php') continue;

        $content      = file_get_contents($file->getPathname());
        $relativePath = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $file->getPathname());

        if (str_contains($content, 'DB::') || str_contains($content, 'use Illuminate\Support\Facades\DB')) {
            $errors[] = "[§3] DB facade in Domain layer: {$relativePath}";
        } else {
            $passed++;
        }
    }
}

// ─────────────────────────────────────────
// RESULTS
// ─────────────────────────────────────────
echo "\n" . str_repeat('─', 50) . "\n";
echo "📊 RESULTS\n";
echo str_repeat('─', 50) . "\n\n";

echo "✅ Passed checks: {$passed}\n";

if (!empty($warnings)) {
    echo "\n⚠️  WARNINGS (" . count($warnings) . "):\n";
    foreach ($warnings as $w) {
        echo "   {$w}\n";
    }
}

if (!empty($errors)) {
    echo "\n❌ ERRORS (" . count($errors) . ") — These block merge:\n";
    foreach ($errors as $e) {
        echo "   {$e}\n";
    }

    echo "\n🚨 VALIDATION FAILED — Fix errors before merge.\n\n";
    exit(1);
}

if (empty($warnings) && empty($errors)) {
    echo "\n🎉 All checks passed! Code is rulebook-compliant.\n\n";
} else {
    echo "\n⚠️  Passed with warnings. Review warnings before merge.\n\n";
}

exit(0);
