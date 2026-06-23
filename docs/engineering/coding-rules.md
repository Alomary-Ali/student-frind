# Coding Rules — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Technical Lead / Chief Architect
**Enforcement:** Laravel Pint + PHPStan Level 8

---

## 1. Code Organization
### File Structure

All code must follow the established module structure:

```
src/Modules/{ModuleName}/
├── Application/       # Use cases, commands, queries, DTOs
├── Domain/            # Entities, value objects, domain events
├── Infrastructure/    # Repository implementations, external adapters
├── Presentation/       # Controllers, requests, resources
└── Tests/             # Unit, feature, integration tests
```

### File Naming

- **Classes:** PascalCase (e.g., `StudentEnrollmentUseCase`)
- **Methods:** camelCase (e.g., `enrollStudent`)
- **Variables:** camelCase (e.g., `$studentId`)
- **Constants:** UPPER_SNAKE_CASE (e.g., `MAX_ENROLLMENT_CREDIT`)

---

## 2. Code Style

### PHP Standards

- Follow PSR-12 coding standard
- Use Laravel Pint for formatting
- Maximum line length: 120 characters
- Use 4 spaces for indentation (no tabs)

### JavaScript Standards

- Use ES6+ syntax
- Follow Airbnb JavaScript Style Guide
- Use Prettier for formatting
- Maximum line length: 100 characters

### CSS Standards

- Use Tailwind CSS utility classes
- Follow BEM naming for custom CSS
- Use CSS variables for design tokens
- Maximum specificity: avoid !important

---

## 3. Code Quality

### Static Analysis

- **PHPStan:** Level 8 (strictest)
- **PHPMD:** Mess detection
- **PHP-CS-Fixer:** Code style fixing

### Code Metrics

| Metric | Limit | Tool |
|--------|-------|------|
| Cyclomatic Complexity | 10 | PHPMD |
| Class Length | 300 lines | Custom check |
| Method Length | 30 lines | Custom check |
| Nesting Depth | 3 levels | Custom check |
| Parameter Count | 5 parameters | Custom check |

### Code Review

- All code must be reviewed before merge
- Minimum 1 approval required
- Automated checks must pass
- Security review for sensitive changes

---

## 4. Error Handling

### Exception Handling

```php
// Domain exceptions
class StudentNotFoundException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("Student not found: {$id}");
    }
}

// Use in application layer
try {
    $student = $this->repository->findById($id);
} catch (StudentNotFoundException $e) {
    Log::warning('Student not found', ['id' => $id]);
    throw $e;
}
```

### Logging

- Use Laravel's Log facade
- Log levels: debug, info, notice, warning, error, critical, alert, emergency
- Include context in log messages
- Never log sensitive data (passwords, tokens)

---

## 5. Performance

### Database Queries

- Use eager loading to prevent N+1 queries
- Avoid select * (specify columns)
- Use database indexes appropriately
- Query caching for frequently accessed data

### Memory Management

- Avoid memory leaks in long-running processes
- Use lazy loading for large datasets
- Implement pagination for list operations
- Use queue for heavy operations

### Caching

- Use Redis for caching
- Cache keys must be descriptive
- Set appropriate TTL values
- Implement cache invalidation

---

## 6. Security

### Input Validation

- Validate all user inputs
- Use Laravel Form Requests
- Sanitize data before storage
- Never trust client-side validation

### Output Escaping

- Always escape output in Blade templates
- Use Laravel's {{ }} syntax
- Use {!! !!} only for trusted HTML
- Implement Content Security Policy

### Authentication & Authorization

- Use Laravel's authentication system
- Implement proper authorization checks
- Use policies for access control
- Log all authentication attempts

---

## 7. Testing

### Test Coverage

- **Unit Tests:** 90%+ coverage for domain layer
- **Feature Tests:** 80%+ coverage for application layer
- **Integration Tests:** Key workflows only

### Test Organization

```
Tests/
├── Unit/           # Domain entities, value objects
├── Feature/        # HTTP endpoints, use case flows
└── Integration/    # Cross-module workflows
```

### Test Naming

- Use descriptive test names
- Follow pattern: `test_[scenario]_[expected_result]`
- Use arrange-act-assert pattern
- One assertion per test when possible

---

## 8. Documentation

### Code Comments

- Document public methods with PHPDoc
- Explain complex algorithms
- Document business rules
- Avoid obvious comments

### API Documentation

- Use OpenAPI/Swagger for REST APIs
- Document all endpoints
- Include request/response examples
- Document authentication requirements

---

## 9. Git Workflow

### Branch Naming

- `feature/{module}-{description}` (e.g., `feature/academic-gpa-calculation`)
- `fix/{module}-{description}` (e.g., `fix/shared-auth-validation`)
- `refactor/{module}-{description}` (e.g., `refactor/career-profile-repository`)
- `docs/{description}` (e.g., `docs/update-architecture`)

### Commit Messages

```
type(scope): description

Types: feat, fix, refactor, test, docs, chore, style

Examples:
feat(academic): add GPA calculation use case
fix(shared): correct email validation in User value object
refactor(career-profile): extract portfolio publishing to dedicated use case
```

### Pull Request Process

1. Create feature branch
2. Make changes with descriptive commits
3. Create pull request with description
4. Request review from team members
5. Address review feedback
6. Ensure CI checks pass
7. Merge to main branch

---

## 10. Code Smells

### Common Anti-Patterns

| Anti-Pattern | Detection | Refactoring |
|--------------|-----------|-------------|
| God Class | >300 lines | Extract responsibilities |
| Long Method | >30 lines | Extract private methods |
| Duplicated Code | Similar blocks | Extract to method |
| Magic Numbers | Hardcoded values | Use constants |
| Feature Envy | Using other class methods | Move method |
| Inappropriate Intimacy | Accessing internals | Use public interface |

---

## Enforcement

### Pre-commit Hooks

- Run Laravel Pint
- Run PHPStan
- Run tests
- Check for TODO comments

### CI/CD Pipeline

- Run all automated checks
- Execute test suite
- Run security scans
- Generate code coverage report
- Deploy on success

### Violation Handling

- **Minor violations:** Fix before merge
- **Major violations:** Block merge
- **Critical violations:** Escalate to tech lead

---

## References

- Architecture: `.memory/architecture.md`
- Coding Standards: `.memory/coding-standards.md`
- Design System: `docs/design-system/`
- ADRs: `docs/adr/`
