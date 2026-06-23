# Academic Module

## Purpose

Manages the complete academic lifecycle of a student: study plans, course enrollments, grade recording, GPA computation, academic standing, and graduation progress tracking.

---

## Domain Model

### Primary Aggregate Roots
- `Student` — academic profile linked to Shared `User`
- `AcademicPlan` — curriculum assignment
- `GraduationPath` — progress toward graduation

### Entities
- `Course`, `Semester`, `Curriculum`, `Enrollment`, `AcademicRecord`, `CurriculumCourse`

### Value Objects
- `Gpa`, `Credits`, `Grade`, `StudentId`, `CourseId`, `SemesterId`, `CurriculumId`, `AcademicPlanId`, `EnrollmentId`, `AcademicRecordId`, `GraduationPathId`

### Enums
- `AcademicStatus`, `AcademicStanding`, `GradeLetter`, `EnrollmentStatus`, `AcademicPlanStatus`

---

## Use Cases

| Use Case | Type | Status |
|----------|------|--------|
| `CreateStudent` | Command | ✅ |
| `CreateCourse` | Command | ✅ |
| `AssignAcademicPlan` | Command | ✅ |
| `EnrollStudentInCourse` | Command | ✅ |
| `RecordAcademicGrade` | Command | ✅ |
| `GetStudentAcademicProfile` | Query | ✅ |
| `ListCourses` | Query | ✅ |
| `ListCurriculumCourses` | Query | ✅ |
| `GetGraduationProgress` | Query | ✅ |

---

## Domain Events Published

- `StudentCreated`
- `CourseCreated`
- `StudentEnrolled`
- `AcademicPlanAssigned`
- `CourseCompleted`
- `SemesterCompleted` (defined, trigger pending)
- `GpaUpdated`

---

## Public Contracts (Exposed to Other Modules)

- `AcademicPlanReaderInterface` — read-only student profile, plan, graduation progress

---

## Dependencies

- **Shared** — User identity, EventDispatcher

---

## API Base Path

`/api/v1/academic`

See `Docs/api-endpoints.md` for full documentation.

---

## Status

Phase: **Phase 2 — Academic Core**  
Implementation: **Complete (MVP)** — 2026-06-16
