# Academic Module — Domain Events

---

## StudentCreated

| Field | Value |
|-------|-------|
| **Purpose** | Notify other modules that a student academic profile was created |
| **Trigger** | `Student::create()` factory method |
| **Payload** | `studentId`, `userId`, `studentNumber`, `occurredAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Guidance (advisor assignment), Analytics (cohort tracking) |

---

## CourseCreated

| Field | Value |
|-------|-------|
| **Purpose** | Announce new course in catalog |
| **Trigger** | `Course::create()` |
| **Payload** | `courseId`, `code`, `title`, `creditHours`, `occurredAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Skills (competency mapping), Search index |

---

## StudentEnrolled

| Field | Value |
|-------|-------|
| **Purpose** | Notify enrollment in course for a semester |
| **Trigger** | `Enrollment::create()` |
| **Payload** | `enrollmentId`, `studentId`, `courseId`, `semesterId`, `enrolledAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Productivity (schedule sync), Guidance (workload alerts) |

---

## AcademicPlanAssigned

| Field | Value |
|-------|-------|
| **Purpose** | Student assigned to curriculum/academic plan |
| **Trigger** | `AcademicPlan::assign()` |
| **Payload** | `academicPlanId`, `studentId`, `curriculumId`, `assignedAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Guidance (advisor dashboard), Analytics |

---

## CourseCompleted

| Field | Value |
|-------|-------|
| **Purpose** | Grade recorded and course marked complete |
| **Trigger** | `AcademicRecord::record()` |
| **Payload** | `enrollmentId`, `studentId`, `courseId`, `grade`, `gradePoints`, `completedAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Skills (credit toward competencies), Guidance (early alerts) |

---

## SemesterCompleted

| Field | Value |
|-------|-------|
| **Purpose** | Student completed all courses for a semester |
| **Trigger** | Semester completion use case (future) |
| **Payload** | `studentId`, `semesterId`, `semesterGpa`, `completedAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Analytics (semester reports) |

---

## GpaUpdated

| Field | Value |
|-------|-------|
| **Purpose** | Cumulative GPA changed after grade recording |
| **Trigger** | `Student::updateGpa()` |
| **Payload** | `studentId`, `previousGpa`, `newGpa`, `updatedAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Guidance (probation alerts when GPA < 2.0), Analytics |
