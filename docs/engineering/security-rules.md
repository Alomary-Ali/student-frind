# Security Rules — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** Security Lead / Chief Architect
**Enforcement:** Security audits + CI/CD security scans

---

## 1. Security Principles

### Core Security Principles

- **Defense in Depth:** Multiple layers of security controls
- **Least Privilege:** Users and systems have minimum necessary access
- **Security by Design:** Security considered from the start
- **Fail Securely:** System fails to a secure state
- **Zero Trust:** Verify every request, trust nothing

### Threat Model

The platform faces the following primary threats:
- Unauthorized access to student data
- Data breaches and information disclosure
- Privilege escalation attacks
- Injection attacks (SQL, XSS, CSRF)
- Authentication bypass
- Session hijacking
- Data tampering

---

## 2. Authentication

### Authentication Requirements

- **Strong Password Policy:**
  - Minimum 12 characters
  - Must include uppercase, lowercase, numbers, special characters
  - Password history: Last 10 passwords
  - Password expiration: 90 days
  - Account lockout: 5 failed attempts for 30 minutes

- **Multi-Factor Authentication (MFA):**
  - Required for all administrative accounts
  - Optional for student accounts (recommended)
  - Supports TOTP (Time-based One-Time Password)
  - Supports SMS verification (fallback)

- **Session Management:**
  - Session timeout: 30 minutes of inactivity
  - Session regeneration on login
  - Secure session storage (encrypted)
  - Session invalidation on logout

### Implementation

```php
// Password validation
class PasswordValidationRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return strlen($value) >= 12
            && preg_match('/[A-Z]/', $value)
            && preg_match('/[a-z]/', $value)
            && preg_match('/[0-9]/', $value)
            && preg_match('/[^A-Za-z0-9]/', $value);
    }

    public function message(): string
    {
        return 'Password must be at least 12 characters and include uppercase, lowercase, numbers, and special characters.';
    }
}

// Rate limiting for login attempts
Route::post('/login', function () {
    // Login logic
})->middleware('throttle:5,1'); // 5 attempts per minute
```

---

## 3. Authorization

### Authorization Model

- **Role-Based Access Control (RBAC):**
  - Roles: Student, Advisor, Administrator, System
  - Permissions assigned to roles
  - Users assigned to roles

- **Attribute-Based Access Control (ABAC):**
  - Fine-grained access control
  - Based on user attributes (department, year, etc.)
  - Based on resource attributes (ownership, sensitivity)

### Implementation

```php
// Policy example
class StudentPolicy
{
    public function view(User $user, Student $student): bool
    {
        // Students can view their own data
        if ($user->isStudent() && $user->student->id === $student->id) {
            return true;
        }

        // Advisors can view their advisees
        if ($user->isAdvisor() && $user->advisor->hasAdvisee($student)) {
            return true;
        }

        // Administrators can view all students
        return $user->isAdmin();
    }

    public function update(User $user, Student $student): bool
    {
        // Only students can update their own data
        return $user->isStudent() && $user->student->id === $student->id;
    }
}

// Middleware usage
Route::get('/students/{student}', [StudentController::class, 'show'])
    ->middleware('can:view,student');
```

---

## 4. Data Protection

### Data Classification

| Classification | Description | Protection Level |
|----------------|-------------|------------------|
| Public | Non-sensitive information | Standard |
| Internal | Internal use only | Standard + Access Control |
| Confidential | Sensitive student data | Encryption + Strict Access Control |
| Restricted | Highly sensitive (PII, financial) | Encryption + MFA + Audit Logging |

### Encryption Requirements

- **At Rest:**
  - Database: AES-256 encryption
  - File storage: AES-256 encryption
  - Configuration: Encrypted secrets

- **In Transit:**
  - TLS 1.3 for all communications
  - HTTPS only (HTTP disabled in production)
  - Certificate pinning for mobile apps

### Implementation

```php
// Encrypting sensitive data
use Illuminate\Support\Facades\Crypt;

$encryptedData = Crypt::encrypt($sensitiveData);
$decryptedData = Crypt::decrypt($encryptedData);

// Database encryption
class Student extends Model
{
    protected $casts = [
        'ssn' => 'encrypted',
        'phone' => 'encrypted',
    ];
}
```

---

## 5. Input Validation

### Validation Rules

- **Client-Side Validation:** User experience only
- **Server-Side Validation:** Mandatory for all inputs
- **Whitelist Approach:** Allow only known good values
- **Sanitization:** Remove malicious content

### Common Vulnerabilities

- **SQL Injection:** Use parameterized queries
- **XSS (Cross-Site Scripting):** Escape all output
- **CSRF (Cross-Site Request Forgery):** Use CSRF tokens
- **Command Injection:** Avoid shell commands, use whitelisting

### Implementation

```php
// Form request validation
class CreateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Student::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'gpa' => 'required|numeric|min:0|max:4',
        ];
    }
}

// Output escaping in Blade
{{ $student->name }} // Automatically escaped
{!! $student->bio !!} // Only for trusted HTML
```

---

## 6. API Security

### API Authentication

- **API Keys:** For external integrations
- **JWT (JSON Web Tokens):** For SPA and mobile apps
- **OAuth 2.0:** For third-party applications
- **API Rate Limiting:** Prevent abuse

### Implementation

```php
// API key middleware
class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');

        if (!$apiKey || !ApiKey::isValid($apiKey)) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        return $next($request);
    }
}

// Rate limiting
Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {
    // API routes - 60 requests per minute
});
```

---

## 7. Logging and Monitoring

### Security Logging

- **Authentication Events:** Login, logout, failed attempts
- **Authorization Events:** Access denied, privilege escalation
- **Data Access:** Access to sensitive data
- **Configuration Changes:** System modifications
- **Security Incidents:** Detected threats

### Log Format

```php
Log::info('User login', [
    'user_id' => $user->id,
    'ip_address' => $request->ip(),
    'user_agent' => $request->userAgent(),
    'timestamp' => now(),
]);

Log::warning('Failed login attempt', [
    'email' => $request->email,
    'ip_address' => $request->ip(),
    'attempts' => $attempts,
]);
```

### Monitoring

- **Real-time Alerting:** Security events
- **Dashboard:** Security metrics
- **Audit Trail:** Complete audit log
- **Intrusion Detection:** Automated threat detection

---

## 8. Dependency Security

### Dependency Management

- **Regular Updates:** Keep dependencies up to date
- **Vulnerability Scanning:** Automated scanning
- **Patch Management:** Apply security patches promptly
- **Supply Chain Security:** Verify package integrity

### Implementation

```bash
# Security audit
composer audit
npm audit

# Update dependencies
composer update
npm update

# Check for vulnerabilities
composer require --dev roave/security-advisories:dev-latest
```

---

## 9. Secure Development Lifecycle

### Development Phase

- **Security Requirements:** Defined upfront
- **Threat Modeling:** Identify potential threats
- **Secure Coding:** Follow security best practices
- **Code Review:** Security-focused review

### Testing Phase

- **Security Testing:** Automated and manual
- **Penetration Testing:** External security assessment
- **Vulnerability Scanning:** Automated scanning tools
- **Security Review:** Final security assessment

### Deployment Phase

- **Security Configuration:** Secure deployment
- **Monitoring:** Continuous security monitoring
- **Incident Response:** Prepared response plan
- **Post-Deployment Review:** Security assessment

---

## 10. Incident Response

### Incident Response Plan

1. **Detection:** Identify security incident
2. **Containment:** Limit incident impact
3. **Eradication:** Remove threat
4. **Recovery:** Restore normal operations
5. **Lessons Learned:** Document and improve

### Incident Categories

- **Critical:** Immediate response required (< 1 hour)
- **High:** Response within 4 hours
- **Medium:** Response within 24 hours
- **Low:** Response within 72 hours

### Reporting

- **Internal:** Security team, management
- **External:** Users, authorities (if required)
- **Documentation:** Complete incident report

---

## 11. Compliance

### Regulatory Compliance

- **GDPR:** General Data Protection Regulation (EU)
- **FERPA:** Family Educational Rights and Privacy Act (US)
- **SOC 2:** Service Organization Control 2
- **ISO 27001:** Information Security Management

### Data Privacy

- **Consent:** Explicit consent for data processing
- **Data Minimization:** Collect only necessary data
- **Right to Access:** Users can access their data
- **Right to Deletion:** Users can request data deletion
- **Data Portability:** Users can export their data

---

## 12. Security Checklist

### Pre-Deployment Checklist

- [ ] All dependencies updated and scanned
- [ ] Security tests passed
- [ ] Penetration testing completed
- [ ] Code review completed
- [ ] Configuration reviewed
- [ ] Monitoring configured
- [ ] Incident response plan prepared
- [ ] Documentation updated

### Ongoing Security Tasks

- [ ] Weekly dependency updates
- [ ] Monthly security scans
- [ ] Quarterly penetration testing
- [ ] Annual security audit
- [ ] Continuous monitoring
- [ ] Regular security training

---

## Enforcement

### Security Reviews

- **Code Review:** Security-focused review for all changes
- **Architecture Review:** Security assessment for new features
- **Third-Party Review:** External security assessment
- **Compliance Review:** Regulatory compliance check

### Violation Handling

- **Security Vulnerabilities:** Immediate fix required
- **Policy Violations:** Disciplinary action
- **Data Breaches:** Incident response + notification
- **Non-Compliance:** Remediation + reporting

---

## References

- Architecture: `.memory/architecture.md`
- Coding Standards: `.memory/coding-standards.md`
- OWASP Top 10: https://owasp.org/www-project-top-ten/
- Laravel Security: https://laravel.com/docs/security
