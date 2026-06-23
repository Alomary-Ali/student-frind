# Academic Module — API Documentation

**Base URL:** `/api/v1/academic`  
**Format:** JSON  
**Auth:** Laravel Sanctum (Bearer token) for protected endpoints

---

## Response Format

### Success
```json
{
  "success": true,
  "message": "",
  "data": {}
}
```

### Error
```json
{
  "success": false,
  "code": "NOT_FOUND",
  "message": "Student not found.",
  "errors": {}
}
```

---

## Endpoints

### Students

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/students` | No | Create academic profile for user |
| GET | `/students/{studentId}` | No | Get student academic profile |
| GET | `/students/{studentId}/graduation-progress` | No | Get graduation progress |

#### POST `/students`
```json
{
  "user_id": "uuid",
  "student_number": "STU-2026-001",
  "institution_id": "uuid (optional)"
}
```

### Courses

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/courses` | No | List active courses |
| POST | `/courses` | Yes | Create course (admin) |

#### POST `/courses`
```json
{
  "code": "CS101",
  "title": "Introduction to Computer Science",
  "description": "...",
  "credit_hours": 3,
  "institution_id": "uuid (optional)"
}
```

### Academic Plans

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/plans` | Yes | Assign academic plan to student |

#### POST `/plans`
```json
{
  "student_id": "uuid",
  "curriculum_id": "uuid",
  "institution_id": "uuid (optional)",
  "estimated_graduation_date": "2028-06-01 (optional)"
}
```

### Enrollments

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/enrollments` | Yes | Enroll student in course |

#### POST `/enrollments`
```json
{
  "student_id": "uuid",
  "course_id": "uuid",
  "semester_id": "uuid"
}
```

### Academic Records

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/records` | Yes | Record grade for enrollment |

#### POST `/records`
```json
{
  "enrollment_id": "uuid",
  "grade": "A"
}
```

**Valid grades:** A, A-, B+, B, B-, C+, C, C-, D+, D, F

---

## Web Routes

Available in `routes/web.php`:

| Method | Path | Controller | Description |
|--------|------|------------|-------------|
| GET | `/academic/profile` | `AcademicProfileController` | الملف الأكاديمي للطالب |
| GET | `/academic/progress` | `AcademicProgressController` | مؤشرات الأداء والتقدم |
| GET | `/academic/graduation-map` | `GraduationMapController` | خريطة التخرج التفاعلية |
| GET | `/academic/plan` | `AcademicPlanController` | الخطة الدراسية التفاعلية |
| GET | `/academic/curriculum` | `ListCurriculumCoursesController` | مفردات الخطة الدراسية |

---

## Status Codes

| Code | Usage |
|------|-------|
| 200 | Successful GET |
| 201 | Successful POST (create) |
| 404 | Resource not found |
| 409 | Business rule conflict |
| 422 | Validation error |
| 401 | Unauthenticated (protected routes) |
