# Testing Rules — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Technical Lead / QA Lead
**Enforcement:** CI/CD Pipeline

---

## 1. Testing Philosophy

### Testing Pyramid

```
        /\
       /  \      E2E Tests (5%)
      /____\
     /      \    Integration Tests (15%)
    /________\
   /          \  Feature Tests (30%)
  /____________\
 /              \ Unit Tests (50%)
/________________\
```

### Test Coverage Targets

| Layer | Coverage Target | Priority |
|-------|----------------|----------|
| Domain (Entities, Value Objects) | 90%+ | Critical |
| Application (Use Cases) | 80%+ | Critical |
| Infrastructure (Repositories) | 70%+ | High |
| Presentation (Controllers) | 60%+ | Medium |
| Integration Workflows | Key flows only | High |

---

## 2. Test Organization

### Directory Structure

```
src/Modules/{ModuleName}/Tests/
├── Unit/
│   ├── Domain/
│   │   ├── Entities/
│   │   └── ValueObjects/
│   └── Application/
│       ├── UseCases/
│       └── Services/
├── Feature/
│   ├── Controllers/
│   └── UseCases/
└── Integration/
    └── Workflows/
```

### Test Naming Convention

```php
// Unit tests
class StudentTest extends TestCase
{
    public function test_student_can_be_created_with_valid_data(): void
    {
        // Test implementation
    }

    public function test_student_enrollment_fails_when_credit_limit_exceeded(): void
    {
        // Test implementation
    }
}

// Feature tests
class EnrollStudentInCourseTest extends TestCase
{
    public function test_user_can_enroll_in_course(): void
    {
        // Test implementation
    }

    public function test_enrollment_fails_without_authentication(): void
    {
        // Test implementation
    }
}
```

---

## 3. Unit Testing

### Domain Layer Tests

```php
<?php

namespace Modules\Academic\Tests\Unit\Domain\Entities;

use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Domain\Exceptions\InvalidGpaException;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{
    public function test_student_can_be_created_with_valid_data(): void
    {
        $studentId = StudentId::generate();
        $student = Student::create(
            $studentId,
            'John Doe',
            'john@example.com'
        );

        $this->assertSame($studentId, $student->id());
        $this->assertSame('John Doe', $student->name());
        $this->assertSame('john@example.com', $student->email());
    }

    public function test_gpa_must_be_between_0_and_4(): void
    {
        $this->expectException(InvalidGpaException::class);

        $student = Student::create(
            StudentId::generate(),
            'John Doe',
            'john@example.com'
        );

        $student->updateGpa(5.0); // Invalid GPA
    }
}
```

### Value Object Tests

```php
<?php

namespace Modules\Academic\Tests\Unit\Domain\ValueObjects;

use Modules\Academic\Domain\ValueObjects\GradePoint;
use Modules\Academic\Domain\Exceptions\InvalidGradePointException;
use PHPUnit\Framework\TestCase;

class GradePointTest extends TestCase
{
    public function test_grade_point_must_be_between_0_and_4(): void
    {
        $this->expectException(InvalidGradePointException::class);
        GradePoint::of(5.0);
    }

    public function test_grade_point_equality(): void
    {
        $gpa1 = GradePoint::of(3.5);
        $gpa2 = GradePoint::of(3.5);
        $gpa3 = GradePoint::of(3.6);

        $this->assertTrue($gpa1->equals($gpa2));
        $this->assertFalse($gpa1->equals($gpa3));
    }
}
```

---

## 4. Feature Testing

### Controller Tests

```php
<?php

namespace Modules\Academic\Tests\Feature\Controllers;

use Modules\Academic\Application\UseCases\EnrollStudentInCourse;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Infrastructure\Persistence\EloquentStudentRepository;
use Modules\Academic\Infrastructure\Persistence\EloquentCourseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollStudentInCourseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_enroll_in_course(): void
    {
        // Arrange
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $this->actingAs($student->user);

        // Act
        $response = $this->postJson("/api/courses/{$course->id}/enroll", [
            'student_id' => $student->id->value(),
            'academic_term' => '2026-Fall',
        ]);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'student_id',
                    'course_id',
                    'academic_term',
                    'enrolled_at',
                ]
            ]);
    }

    public function test_unauthenticated_user_cannot_enroll_in_course(): void
    {
        // Arrange
        $course = Course::factory()->create();

        // Act
        $response = $this->postJson("/api/courses/{$course->id}/enroll", [
            'student_id' => 'some-id',
            'academic_term' => '2026-Fall',
        ]);

        // Assert
        $response->assertStatus(401);
    }
}
```

### Use Case Tests

```php
<?php

namespace Modules\Academic\Tests\Feature\Application\UseCases;

use Modules\Academic\Application\UseCases\EnrollStudentInCourse;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Domain\Exceptions\CourseNotFoundException;
use Modules\Academic\Domain\Exceptions\EnrollmentLimitExceededException;
use Modules\Academic\Infrastructure\Persistence\EloquentStudentRepository;
use Modules\Academic\Infrastructure\Persistence\EloquentCourseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollStudentInCourseTest extends TestCase
{
    use RefreshDatabase;

    private EnrollStudentInCourse $useCase;
    private EloquentStudentRepository $studentRepository;
    private EloquentCourseRepository $courseRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studentRepository = new EloquentStudentRepository();
        $this->courseRepository = new EloquentCourseRepository();
        $this->useCase = new EnrollStudentInCourse(
            $this->studentRepository,
            $this->courseRepository,
            app('events')
        );
    }

    public function test_student_can_be_enrolled_in_course(): void
    {
        // Arrange
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $dto = new EnrollStudentInCourseDto(
            $student->id->value(),
            $course->id->value(),
            '2026-Fall'
        );

        // Act
        $result = $this->useCase->execute($dto);

        // Assert
        $this->assertSame($student->id->value(), $result->studentId);
        $this->assertSame($course->id->value(), $result->courseId);
        $this->assertSame('2026-Fall', $result->academicTerm);
    }

    public function test_enrollment_fails_when_student_not_found(): void
    {
        // Arrange
        $course = Course::factory()->create();

        $dto = new EnrollStudentInCourseDto(
            'non-existent-id',
            $course->id->value(),
            '2026-Fall'
        );

        // Assert
        $this->expectException(StudentNotFoundException::class);
        $this->useCase->execute($dto);
    }
}
```

---

## 5. Integration Testing

### Cross-Module Workflow Tests

```php
<?php

namespace Modules\Academic\Tests\Integration;

use Modules\Academic\Domain\Events\StudentEnrolled;
use Modules\Guidance\Application\Listeners\UpdateAcademicStandingOnEnrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class StudentEnrollmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_enrollment_triggers_academic_standing_update(): void
    {
        // Arrange
        Event::fake();

        $student = Student::factory()->create();
        $course = Course::factory()->create();

        // Act
        $this->useCase->execute(new EnrollStudentInCourseDto(
            $student->id->value(),
            $course->id->value(),
            '2026-Fall'
        ));

        // Assert
        Event::assertDispatched(StudentEnrolled::class);
    }
}
```

---

## 6. Test Data Management

### Factories

```php
<?php

namespace Modules\Academic\Infrastructure\Database\Factories;

use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'id' => StudentId::generate()->value(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'gpa' => fake()->randomFloat(1, 2.0, 4.0),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withHighGpa(): self
    {
        return $this->state(fn (array $attributes) => [
            'gpa' => fake()->randomFloat(1, 3.5, 4.0),
        ]);
    }

    public function withLowGpa(): self
    {
        return $this->state(fn (array $attributes) => [
            'gpa' => fake()->randomFloat(1, 2.0, 2.5),
        ]);
    }
}
```

### Seeders

```php
<?php

namespace Database\Seeders;

use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Entities\Course;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        Student::factory()->count(10)->create();
        Course::factory()->count(20)->create();
    }
}
```

---

## 7. Test Execution

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
php artisan test --testsuite=Integration

# Run specific test file
php artisan test tests/Unit/Domain/Entities/StudentTest.php

# Run with coverage
php artisan test --coverage

# Run in parallel
php artisan test --parallel
```

### CI/CD Integration

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: php artisan test --coverage
      - name: Upload Coverage
        uses: codecov/codecov-action@v2
```

---

## 8. Test Quality

### Test Smells

| Smell | Example | Solution |
|-------|---------|----------|
| Fragile Tests | Tests break on UI changes | Test behavior, not implementation |
| Slow Tests | Tests take >5 seconds | Use in-memory database, mock external services |
| Assertion Roulette | Multiple unrelated assertions | Split into separate tests |
| Mystery Guest | Unclear test setup | Use descriptive factory methods |
| Test Code Duplication | Similar test code | Extract to test helpers |

### Best Practices

- **One assertion per test** when possible
- **Arrange-Act-Assert** pattern
- **Descriptive test names** that explain what and why
- **Test behavior, not implementation**
- **Use factories** for test data
- **Mock external dependencies**
- **Clean up test data** after each test
- **Run tests in isolation**

---

## 9. Test Documentation

### Test Documentation Requirements

- Document complex test scenarios
- Explain business rules being tested
- Document test data requirements
- Document test environment setup

### Test Report Template

```markdown
# Test Report: {Feature Name}

## Test Coverage
- Unit: XX%
- Feature: XX%
- Integration: XX%

## Test Results
- Total Tests: XX
- Passed: XX
- Failed: XX
- Skipped: XX

## Issues Found
1. [Issue description]
2. [Issue description]

## Recommendations
1. [Recommendation]
2. [Recommendation]
```

---

## 10. Enforcement

### Pre-commit Hooks

```bash
#!/bin/bash
# .git/hooks/pre-commit

# Run tests
php artisan test --stop-on-failure

if [ $? -ne 0 ]; then
    echo "Tests failed. Commit aborted."
    exit 1
fi
```

### CI/CD Requirements

- All tests must pass before merge
- Coverage thresholds must be met
- Security scans must pass
- Performance tests must pass

### Violation Handling

- **Failed tests:** Block merge
- **Low coverage:** Request improvement
- **Flaky tests:** Fix before merge
- **Slow tests:** Optimize or mark as slow

---

## References

- Architecture: `.memory/architecture.md`
- Coding Standards: `.memory/coding-standards.md`
- Testing Documentation: Laravel Testing Docs
- PHPUnit Documentation: https://phpunit.de/
