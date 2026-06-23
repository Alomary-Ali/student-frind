# API Standards & Contract
## رفيق الطالب — REST API Governance

---

## 1. Versioning

```
/api/v1/resource           ← current
/api/v2/resource           ← future breaking changes
```

## 2. Standard Response Contract

### Success
```json
{
    "success": true,
    "data": { },
    "message": "عملية ناجحة.",
    "errors": null,
    "meta": {
        "page": 1,
        "per_page": 20,
        "total": 150,
        "last_page": 8
    }
}
```

### Error
```json
{
    "success": false,
    "data": null,
    "message": "فشل التحقق من صحة البيانات.",
    "errors": {
        "grade": ["حقل الدرجة مطلوب."]
    },
    "meta": null
}
```

## 3. HTTP Status Codes

| الحالة | الكود | الاستخدام |
|--------|-------|----------|
| Success | 200 | GET, PUT |
| Created | 201 | POST ناجح |
| No Content | 204 | DELETE ناجح |
| Validation Error | 422 | FormRequest فشل |
| Unauthenticated | 401 | لا token |
| Unauthorized | 403 | لا صلاحية |
| Not Found | 404 | مورد غير موجود |
| Rate Limited | 429 | تجاوز الحد |
| Server Error | 500 | خطأ غير متوقع |

## 4. Pagination (إلزامي لكل List endpoints)

```
GET /api/v1/academic/courses?page=1&per_page=20&sort=name&order=asc
```

## 5. Filtering & Sorting

```
GET /api/v1/productivity/tasks?status=pending&priority=high&sort=due_date&order=asc
```

## 6. Authentication

```http
Authorization: Bearer {sanctum_token}
```

## 7. Error Codes

| الكود | المعنى |
|-------|--------|
| `NOT_FOUND` | المورد غير موجود |
| `VALIDATION_ERROR` | خطأ في البيانات المدخلة |
| `UNAUTHORIZED` | لا صلاحية |
| `DUPLICATE_ENROLLMENT` | تسجيل مكرر |
| `STUDENT_NOT_ELIGIBLE` | الطالب غير مؤهل |
| `ENROLLMENT_NOT_FOUND` | التسجيل غير موجود |
| `RATE_LIMITED` | تجاوز حد الطلبات |

## 8. ApiResponse Helper

```php
// ✅ استخدم دائماً
ApiResponse::success(data: $result, message: 'Grade recorded.', status: 201);
ApiResponse::error(code: 'NOT_FOUND', message: $e->getMessage(), status: 404);

// ❌ لا تستخدم
return response()->json(['data' => $result]); // غير موحَّد
```
