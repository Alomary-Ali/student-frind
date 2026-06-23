<?php

/**
 * Academic Module Code Generator
 * Run: php scripts/generate-academic-module.php
 */

declare(strict_types=1);

$base = dirname(__DIR__) . '/src/Modules/Academic';

$files = [];

// ============================================================================
// EXCEPTIONS
// ============================================================================

$exceptionTemplate = function (string $name, string $messageMethod = ''): string {
    $method = $messageMethod ?: "return '{$name} occurred.';";

    return <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class {$name} extends DomainException
{
    public static function forId(string \$id): self
    {
        return new self("{$name}: resource not found with id {\$id}");
    }

    public static function invalidFormat(string \$value): self
    {
        return new self("{$name}: invalid format for value {\$value}");
    }
}

PHP;
};

$exceptions = [
    'InvalidStudentIdException', 'InvalidCourseIdException', 'InvalidSemesterIdException',
    'InvalidCurriculumIdException', 'InvalidAcademicPlanIdException', 'InvalidEnrollmentIdException',
    'InvalidAcademicRecordIdException', 'InvalidGraduationPathIdException',
    'InvalidGpaException', 'InvalidCreditsException', 'InvalidGradeException',
    'StudentNotFoundException', 'CourseNotFoundException', 'SemesterNotFoundException',
    'CurriculumNotFoundException', 'AcademicPlanNotFoundException', 'EnrollmentNotFoundException',
    'StudentAlreadyExistsException', 'DuplicateEnrollmentException', 'StudentNotEligibleException',
    'CourseNotActiveException', 'AcademicPlanAlreadyAssignedException', 'UnauthorizedAcademicAccessException',
];

foreach ($exceptions as $ex) {
    if (str_starts_with($ex, 'InvalidGpa')) {
        $files["Domain/Exceptions/{$ex}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidGpaException extends DomainException
{
    public static function outOfRange(float \$value): self
    {
        return new self("GPA must be between 0.0 and 4.0, got {\$value}");
    }
}

PHP;
    } elseif (str_starts_with($ex, 'InvalidCredits')) {
        $files["Domain/Exceptions/{$ex}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidCreditsException extends DomainException
{
    public static function outOfRange(int \$value): self
    {
        return new self("Credits must be between 0 and 30, got {\$value}");
    }
}

PHP;
    } elseif (str_starts_with($ex, 'InvalidGrade')) {
        $files["Domain/Exceptions/{$ex}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidGradeException extends DomainException
{
    public static function pointsMismatch(string \$letter, float \$points): self
    {
        return new self("Grade points {\$points} do not match letter grade {\$letter}");
    }
}

PHP;
    } elseif ($ex === 'StudentAlreadyExistsException') {
        $files["Domain/Exceptions/{$ex}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class StudentAlreadyExistsException extends DomainException
{
    public static function forUserId(string \$userId): self
    {
        return new self("Academic profile already exists for user {\$userId}");
    }
}

PHP;
    } elseif ($ex === 'DuplicateEnrollmentException') {
        $files["Domain/Exceptions/{$ex}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class DuplicateEnrollmentException extends DomainException
{
    public static function forStudentAndCourse(string \$studentId, string \$courseId, string \$semesterId): self
    {
        return new self("Student {\$studentId} is already enrolled in course {\$courseId} for semester {\$semesterId}");
    }
}

PHP;
    } elseif ($ex === 'StudentNotEligibleException') {
        $files["Domain/Exceptions/{$ex}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class StudentNotEligibleException extends DomainException
{
    public static function cannotEnroll(string \$studentId, string \$reason): self
    {
        return new self("Student {\$studentId} is not eligible to enroll: {\$reason}");
    }
}

PHP;
    } elseif ($ex === 'CourseNotActiveException') {
        $files["Domain/Exceptions/{$ex}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class CourseNotActiveException extends DomainException
{
    public static function forCourse(string \$courseId): self
    {
        return new self("Course {\$courseId} is not active");
    }
}

PHP;
    } elseif ($ex === 'AcademicPlanAlreadyAssignedException') {
        $files["Domain/Exceptions/{$ex}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class AcademicPlanAlreadyAssignedException extends DomainException
{
    public static function forStudent(string \$studentId): self
    {
        return new self("Student {\$studentId} already has an active academic plan");
    }
}

PHP;
    } elseif ($ex === 'UnauthorizedAcademicAccessException') {
        $files["Domain/Exceptions/{$ex}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class UnauthorizedAcademicAccessException extends DomainException
{
    public static function forAction(string \$action): self
    {
        return new self("Unauthorized to perform action: {\$action}");
    }
}

PHP;
    } else {
        $files["Domain/Exceptions/{$ex}.php"] = $exceptionTemplate($ex);
    }
}

// ============================================================================
// DOMAIN EVENTS
// ============================================================================

$events = [
    'StudentCreated' => ['studentId', 'userId', 'studentNumber', 'occurredAt'],
    'CourseCreated' => ['courseId', 'code', 'title', 'creditHours', 'occurredAt'],
    'StudentEnrolled' => ['enrollmentId', 'studentId', 'courseId', 'semesterId', 'enrolledAt'],
    'AcademicPlanAssigned' => ['academicPlanId', 'studentId', 'curriculumId', 'assignedAt'],
    'CourseCompleted' => ['enrollmentId', 'studentId', 'courseId', 'grade', 'gradePoints', 'completedAt'],
    'SemesterCompleted' => ['studentId', 'semesterId', 'semesterGpa', 'completedAt'],
    'GpaUpdated' => ['studentId', 'previousGpa', 'newGpa', 'updatedAt'],
];

foreach ($events as $eventName => $fields) {
    $params = [];
    $assigns = [];
    foreach ($fields as $field) {
        $type = match ($field) {
            'occurredAt', 'enrolledAt', 'assignedAt', 'completedAt', 'updatedAt' => '\\DateTimeImmutable',
            'creditHours' => 'int',
            'gradePoints', 'previousGpa', 'newGpa', 'semesterGpa' => 'float',
            default => 'string',
        };
        $params[] = "public readonly {$type} \${$field}";
    }
    $paramStr = implode(",\n        ", $params);
    $files["Domain/Events/{$eventName}.php"] = <<<PHP
<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

final class {$eventName}
{
    public function __construct(
        {$paramStr},
    ) {}
}

PHP;
}

// Write all files
$count = 0;
foreach ($files as $relativePath => $content) {
    $fullPath = $base . '/' . $relativePath;
    $dir = dirname($fullPath);
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($fullPath, $content);
    $count++;
}

echo "Generated {$count} files in Academic module.\n";
