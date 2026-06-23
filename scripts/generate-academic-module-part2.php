<?php

declare(strict_types=1);

/**
 * Academic Module Part 2 Generator — Application, Infrastructure, Presentation
 * Run: php scripts/generate-academic-module-part2.php
 */
$base = dirname(__DIR__) . '/src/Modules/Academic';
$count = 0;

function writeFile(string $base, string $path, string $content): void
{
    global $count;
    $full = $base . '/' . $path;
    $dir = dirname($full);
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($full, $content);
    $count++;
}

// ============================================================================
// MIGRATIONS
// ============================================================================

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100000_create_academic_students_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('student_number')->unique();
            $table->string('academic_status');
            $table->string('academic_standing');
            $table->decimal('cumulative_gpa', 3, 2)->default(0);
            $table->uuid('institution_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique('user_id');
            $table->index('institution_id');
            $table->index('academic_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_students');
    }
};

PHP);

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100001_create_academic_courses_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description');
            $table->unsignedTinyInteger('credit_hours');
            $table->boolean('is_active')->default(true);
            $table->uuid('institution_id')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('institution_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_courses');
    }
};

PHP);

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100002_create_academic_semesters_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_semesters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->uuid('institution_id')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_semesters');
    }
};

PHP);

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100003_create_academic_curricula_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_curricula', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description');
            $table->unsignedSmallInteger('total_credits_required');
            $table->uuid('institution_id')->nullable();
            $table->timestamps();

            $table->index('institution_id');
        });

        Schema::create('academic_curriculum_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('curriculum_id');
            $table->uuid('course_id');
            $table->boolean('is_required')->default(true);
            $table->unsignedTinyInteger('semester_order')->default(1);
            $table->timestamps();

            $table->foreign('curriculum_id')->references('id')->on('academic_curricula')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('academic_courses')->cascadeOnDelete();
            $table->unique(['curriculum_id', 'course_id']);
            $table->index('curriculum_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_curriculum_courses');
        Schema::dropIfExists('academic_curricula');
    }
};

PHP);

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100004_create_academic_plans_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('curriculum_id');
            $table->string('status');
            $table->timestamp('assigned_at');
            $table->uuid('institution_id')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->cascadeOnDelete();
            $table->foreign('curriculum_id')->references('id')->on('academic_curricula')->restrictOnDelete();
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_plans');
    }
};

PHP);

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100005_create_academic_enrollments_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('course_id');
            $table->uuid('semester_id');
            $table->string('status');
            $table->timestamp('enrolled_at');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('academic_courses')->restrictOnDelete();
            $table->foreign('semester_id')->references('id')->on('academic_semesters')->restrictOnDelete();
            $table->unique(['student_id', 'course_id', 'semester_id']);
            $table->index('student_id');
            $table->index('semester_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_enrollments');
    }
};

PHP);

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100006_create_academic_records_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('enrollment_id');
            $table->uuid('student_id');
            $table->uuid('course_id');
            $table->string('grade_letter');
            $table->decimal('grade_points', 3, 1);
            $table->timestamp('recorded_at');
            $table->uuid('recorded_by_user_id');
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('academic_enrollments')->cascadeOnDelete();
            $table->foreign('student_id')->references('id')->on('academic_students')->cascadeOnDelete();
            $table->unique('enrollment_id');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};

PHP);

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100007_create_academic_graduation_paths_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_graduation_paths', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('curriculum_id');
            $table->unsignedSmallInteger('credits_earned')->default(0);
            $table->unsignedSmallInteger('credits_required');
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->boolean('is_on_track')->default(true);
            $table->date('estimated_graduation_date')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->cascadeOnDelete();
            $table->foreign('curriculum_id')->references('id')->on('academic_curricula')->restrictOnDelete();
            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_graduation_paths');
    }
};

PHP);

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100008_create_academic_audit_logs_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('actor_user_id');
            $table->string('action');
            $table->string('entity_type');
            $table->uuid('entity_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index('actor_user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_audit_logs');
    }
};

PHP);

writeFile($base, 'Infrastructure/Persistence/Migrations/2026_06_16_100009_create_personal_access_tokens_table.php', <<<'PHP'
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->uuidMorphs('tokenable');
            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};

PHP);

echo "Generated {$count} files (migrations).\n";
