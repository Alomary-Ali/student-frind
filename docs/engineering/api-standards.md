# API Standards — Student Success Platform (SSP)

**Last Updated:** 2026-06-16
**Authority:** API Architect / Chief Architect
**Enforcement:** API documentation + code reviews

---

## 1. API Architecture

### API Style

- **Primary:** RESTful API
- **Alternative:** GraphQL (for specific use cases)
- **Protocol:** HTTPS only (HTTP disabled in production)
- **Format:** JSON (request and response)

### API Versioning

- **URL Versioning:** `/api/v1/`, `/api/v2/`
- **Header Versioning:** `Accept: application/vnd.ssp.v1+json`
- **Strategy:** URL versioning preferred
- **Backward Compatibility:** Maintain at least 2 versions

### Base URL

```
Development: https://api-dev.student-success.com/v1/
Staging: https://api-staging.student-success.com/v1/
Production: https://api.student-success.com/v1/
```

---

## 2. RESTful Conventions

### Resource Naming

- **Plural Nouns:** `/students`, `/courses`, `/enrollments`
- **Kebab-case:** `/course-enrollments`, `/academic-plans`
- **Nesting:** `/students/{id}/enrollments` (max 2 levels)
- **No Verbs:** Use HTTP methods instead

### HTTP Methods

| Method | Usage | Idempotent | Safe |
|--------|-------|------------|------|
| GET | Retrieve resource | Yes | Yes |
| POST | Create resource | No | No |
| PUT | Replace resource | Yes | No |
| PATCH | Partial update | No | No |
| DELETE | Delete resource | Yes | No |

### Status Codes

| Code | Meaning | Usage |
|------|---------|-------|
| 200 | OK | Successful GET, PUT, PATCH |
| 201 | Created | Successful POST |
| 204 | No Content | Successful DELETE |
| 400 | Bad Request | Validation error |
| 401 | Unauthorized | Authentication required |
| 403 | Forbidden | Authorization failed |
| 404 | Not Found | Resource not found |
| 409 | Conflict | Resource conflict |
| 422 | Unprocessable Entity | Validation error |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Server error |

---

## 3. Request Format

### Headers

```http
GET /api/v1/students HTTP/1.1
Host: api.student-success.com
Accept: application/json
Content-Type: application/json
Authorization: Bearer {token}
X-Request-ID: {unique-id}
X-Client-Version: 1.0.0
```

### Request Body

```json
{
  "data": {
    "type": "student",
    "attributes": {
      "name": "John Doe",
      "email": "john@example.com",
      "gpa": 3.5
    }
  }
}
```

### Query Parameters

```http
GET /api/v1/students?page=1&per_page=20&sort=name&order=asc HTTP/1.1
```

---

## 4. Response Format

### Success Response

```json
{
  "data": {
    "id": "uuid",
    "type": "student",
    "attributes": {
      "name": "John Doe",
      "email": "john@example.com",
      "gpa": 3.5
    },
    "relationships": {
      "enrollments": {
        "data": [
          {
            "id": "uuid",
            "type": "enrollment"
          }
        ]
      }
    }
  },
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 100
  }
}
```

### Error Response

```json
{
  "errors": [
    {
      "id": "error-id",
      "status": 422,
      "code": "VALIDATION_ERROR",
      "title": "Validation Error",
      "detail": "The email field is required.",
      "source": {
        "pointer": "/data/attributes/email"
      },
      "meta": {
        "field": "email",
        "rule": "required"
      }
    }
  ]
}
```

### Pagination Response

```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 100,
    "last_page": 5,
    "from": 1,
    "to": 20
  },
  "links": {
    "first": "https://api.student-success.com/v1/students?page=1",
    "last": "https://api.student-success.com/v1/students?page=5",
    "prev": null,
    "next": "https://api.student-success.com/v1/students?page=2"
  }
}
```

---

## 5. Authentication

### Bearer Token

```http
Authorization: Bearer {access_token}
```

### API Key

```http
X-API-Key: {api_key}
```

### OAuth 2.0

```http
POST /oauth/token HTTP/1.1
Content-Type: application/json

{
  "grant_type": "password",
  "client_id": "{client_id}",
  "client_secret": "{client_secret}",
  "username": "{username}",
  "password": "{password}"
}
```

---

## 6. Rate Limiting

### Rate Limits

| Role | Limit | Period |
|------|-------|--------|
| Anonymous | 100 requests | 1 hour |
| Authenticated | 1000 requests | 1 hour |
| Premium | 5000 requests | 1 hour |

### Rate Limit Headers

```http
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1625097600
```

### Implementation

```php
Route::middleware(['auth:api', 'throttle:1000,1'])->group(function () {
    // API routes
});
```

---

## 7. Filtering, Sorting, Pagination

### Filtering

```http
GET /api/v1/students?filter[status]=active&filter[gpa]=gte:3.5
```

### Sorting

```http
GET /api/v1/students?sort=name&order=asc
GET /api/v1/students?sort=-created_at
```

### Pagination

```http
GET /api/v1/students?page=1&per_page=20
```

### Implementation

```php
public function index(Request $request)
{
    $query = Student::query();

    // Filtering
    if ($request->has('filter.status')) {
        $query->where('status', $request->input('filter.status'));
    }

    // Sorting
    if ($request->has('sort')) {
        $sort = $request->input('sort');
        $order = $request->input('order', 'asc');
        $query->orderBy($sort, $order);
    }

    // Pagination
    return StudentResource::collection($query->paginate(20));
}
```

---

## 8. Error Handling

### Error Response Format

```json
{
  "errors": [
    {
      "id": "error-id",
      "status": 400,
      "code": "VALIDATION_ERROR",
      "title": "Validation Error",
      "detail": "The email field is required.",
      "source": {
        "pointer": "/data/attributes/email"
      }
    }
  ]
}
```

### Error Codes

| Code | Description |
|------|-------------|
| VALIDATION_ERROR | Request validation failed |
| AUTHENTICATION_ERROR | Authentication failed |
| AUTHORIZATION_ERROR | Authorization failed |
| NOT_FOUND_ERROR | Resource not found |
| CONFLICT_ERROR | Resource conflict |
| SERVER_ERROR | Internal server error |

### Implementation

```php
try {
    $result = $this->useCase->execute($dto);
    return StudentResource::make($result)->response()->setStatusCode(201);
} catch (ValidationException $e) {
    return response()->json([
        'errors' => $e->errors(),
    ], 422);
} catch (ModelNotFoundException $e) {
    return response()->json([
        'errors' => [
            [
                'status' => 404,
                'code' => 'NOT_FOUND_ERROR',
                'title' => 'Resource Not Found',
                'detail' => 'The requested resource was not found.',
            ],
        ],
    ], 404);
}
```

---

## 9. API Documentation

### OpenAPI/Swagger

```yaml
openapi: 3.0.0
info:
  title: Student Success Platform API
  version: 1.0.0
  description: RESTful API for Student Success Platform

servers:
  - url: https://api.student-success.com/v1
    description: Production server

paths:
  /students:
    get:
      summary: List all students
      responses:
        '200':
          description: Successful response
    post:
      summary: Create a new student
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/Student'
```

### Documentation Requirements

- **All Endpoints:** Documented in OpenAPI/Swagger
- **Examples:** Request and response examples
- **Authentication:** Document authentication methods
- **Error Codes:** Document all error codes
- **Rate Limits:** Document rate limits

---

## 10. API Security

### Security Headers

```http
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
```

### CORS

```php
// config/cors.php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['https://student-success.com'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

### Input Validation

```php
class CreateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
```

---

## 11. API Testing

### Test Structure

```php
<?php

namespace Modules\Academic\Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_students(): void
    {
        Student::factory()->count(10)->create();

        $response = $this->getJson('/api/v1/students');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    public function test_can_create_student(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'gpa' => 3.5,
        ];

        $response = $this->postJson('/api/v1/students', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'attributes' => [
                        'name',
                        'email',
                        'gpa',
                    ],
                ],
            ]);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/students', []);

        $response->assertStatus(401);
    }
}
```

---

## 12. API Versioning Strategy

### URL Versioning

```
/api/v1/students
/api/v2/students
```

### Backward Compatibility

- **Never Break:** Maintain backward compatibility
- **Deprecation:** Announce deprecation 6 months in advance
- **Migration:** Provide migration guide
- **Support:** Support at least 2 versions

### Version Deprecation

```http
Warning: API v1 is deprecated and will be removed on 2026-12-31. Please migrate to v2.
```

---

## Enforcement

### Code Review

- **API Design:** Review API design
- **Documentation:** Review API documentation
- **Security:** Review security implementation
- **Performance:** Review performance impact

### CI/CD

- **API Tests:** Run API tests in CI
- **Documentation:** Generate documentation automatically
- **Security Scans:** Scan for vulnerabilities
- **Performance Tests:** Test API performance

---

## References

- Architecture: `.memory/architecture.md`
- Coding Standards: `.memory/coding-standards.md`
- Security Rules: `docs/engineering/security-rules.md`
- REST API Best Practices: https://restfulapi.net/
- OpenAPI Specification: https://swagger.io/specification/
