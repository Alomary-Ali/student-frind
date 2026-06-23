# Test Strategy
## رفيق الطالب — استراتيجية الاختبار

---

## Coverage Minimums (CI يفشل إذا انخفضت)

| الطبقة | الحد الأدنى | الأولوية |
|--------|-------------|---------|
| Domain Layer (Entities, VOs, Services) | **90%** | Critical |
| Application Layer (Use Cases) | **85%** | Critical |
| Infrastructure (Repositories) | **70%** | High |
| Presentation (Controllers) | **70%** | High |
| **Overall** | **80%** | — |

---

## هيكل الاختبارات

```
tests/
├── Feature/           ← HTTP-level integration tests
└── Unit/              ← Pure unit tests

src/Modules/{Module}/Tests/
├── Unit/              ← Domain + Application unit tests
├── Feature/           ← Module feature tests
└── Integration/       ← Repository + DB integration tests
```

---

## أنواع الاختبارات المطلوبة

### 1. Unit Tests — Domain (الأعلى أولوية)
```php
// مثال: StudentEntityTest
final class StudentEntityTest extends TestCase
{
    public function test_student_cannot_enroll_when_suspended(): void
    {
        $student = Student::reconstitute(..., AcademicStatus::Suspended, ...);
        
        $this->expectException(StudentNotEligibleException::class);
        
        $student->enrollInCourse(...);
    }
}
```

### 2. Unit Tests — Use Cases
```php
final class RecordAcademicGradeTest extends TestCase
{
    public function test_records_grade_updates_gpa_and_dispatches_events(): void
    {
        // Arrange — mock all dependencies
        $students = $this->createMock(StudentRepositoryInterface::class);
        // ...

        // Act
        $result = $useCase->execute($dto);

        // Assert
        $this->assertSame('A', $result['grade']);
        $this->assertGreaterThan(0.0, $result['cumulative_gpa']);
    }
}
```

### 3. Feature Tests — Controllers
```php
final class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_login_with_valid_credentials(): void
    {
        User::factory()->create(['academic_id' => '12345678']);

        $response = $this->post('/login', [
            'academic_id' => '12345678',
            'password'    => 'password',
        ]);

        $response->assertRedirect(route('academic.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_is_rate_limited_after_5_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['academic_id' => '00000000', 'password' => 'wrong']);
        }

        $response = $this->post('/login', ['academic_id' => '00000000', 'password' => 'wrong']);

        $response->assertStatus(429);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/academic/dashboard')->assertRedirect('/login');
        $this->get('/productivity/dashboard')->assertRedirect('/login');
    }
}
```

---

## Critical Tests يجب كتابتها أولاً

- [ ] `LoginTest` — login, logout, rate limiting, guest middleware
- [ ] `DashboardAuthTest` — كل routes محمية بـ auth
- [ ] `StudentEntityTest` — enrollment eligibility, GPA updates
- [ ] `RecordAcademicGradeTest` — grade recording, GPA calculation
- [ ] `GpaCalculationServiceTest` — weighted average calculation
- [ ] `ProductivityDashboardTest` — controller returns correct data
- [ ] `ApiAuthTest` — Sanctum token required on protected endpoints

---

## Test Factories

```php
// database/factories/UserFactory.php
User::factory()->student()->create();
User::factory()->advisor()->suspended()->create();
```

كل Factory يجب أن يدعم:
- States: `student()`, `advisor()`, `admin()`
- States: `active()`, `suspended()`, `graduated()`
