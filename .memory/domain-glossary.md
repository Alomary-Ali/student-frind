# Domain Glossary — Student Success Platform (SSP)

**Last Updated:** 2026-06-15
**Authority:** Domain Expert / Technical Lead
**Rule:** All developers MUST use these terms consistently in code, documentation, and communication.

---

## Ubiquitous Language

> These terms define the shared language between business stakeholders and developers.
> Using different terms for the same concept is strictly forbidden.

---

## A

### Academic Plan
A structured map of all courses a student must complete to satisfy graduation requirements.
- Contains: required courses, elective choices, expected completion timeline
- Owned by: `Academic` module
- Entity type: Aggregate Root

### Academic Standing
The formal classification of a student's academic status.
- Values: `GoodStanding`, `Probation`, `Suspension`, `Dismissed`
- Owned by: `Academic` module
- Entity type: Enum (`AcademicStanding`)

### Academic Term
A defined period of study (semester, trimester, quarter).
- Example: `Fall 2026`, `Spring 2027`
- Owned by: `Academic` module
- Entity type: Value Object

### Advisor
A faculty or staff member assigned to guide a student's academic journey.
- Owned by: `Guidance` module
- Entity type: Entity

### Advising Session
A scheduled or recorded interaction between a student and an advisor.
- Owned by: `Guidance` module
- Entity type: Aggregate Root

---

## C

### Career Profile
The student's professional identity — a curated portfolio, CV, and skill showcase.
- Owned by: `CareerProfile` module
- Entity type: Aggregate Root

### Certificate
A formal credential earned by a student for completing a course or program.
- Owned by: `Skills` module
- Entity type: Entity

### Community Group
A student-led or university-sponsored group around shared interests or goals.
- Owned by: `Community` module
- Entity type: Aggregate Root

### Competency
A measurable skill or knowledge area a student develops over time.
- Owned by: `Skills` module
- Entity type: Entity

### Course
A structured unit of academic instruction offered by the university.
- Owned by: `Academic` module
- Entity type: Entity

### Course Enrollment
The formal registration of a student in a specific course for a given academic term.
- Owned by: `Academic` module
- Entity type: Entity (child of Student aggregate)

---

## E

### Early Alert
A warning signal triggered when a student's indicators fall below defined thresholds.
- Examples: GPA drop, missed attendance, incomplete assignments
- Owned by: `Guidance` module
- Entity type: Domain Event + Entity

### Employer
An external organization that posts opportunities or searches for student talent.
- Owned by: `Opportunities` module
- Entity type: Entity

### Enrollment Status
The current registration state of a student.
- Values: `Active`, `Inactive`, `Graduated`, `Withdrawn`, `Suspended`
- Owned by: `Academic` module
- Entity type: Enum (`EnrollmentStatus`)

---

## G

### Goal
A personal or academic objective a student sets and tracks.
- Owned by: `Productivity` module
- Entity type: Aggregate Root

### GPA (Grade Point Average)
A numerical summary of a student's academic performance on a 4.0 scale.
- Owned by: `Academic` module
- Entity type: Value Object (`GradePoint`)

### Graduation Progress
A computed metric showing how far a student is toward meeting graduation requirements.
- Owned by: `Academic` module
- Entity type: Domain Service output / DTO

### Grade
The formal evaluation result assigned to a student for a course.
- Owned by: `Academic` module
- Entity type: Value Object (`Grade`)

---

## H

### Habit
A recurring behavioral pattern a student tracks for productivity improvement.
- Owned by: `Productivity` module
- Entity type: Entity

---

## I

### Internship
A time-limited work opportunity at an employer for practical experience.
- Owned by: `Opportunities` module
- Entity type: Entity (subtype of Opportunity)

---

## M

### Mentor
An industry professional who volunteers to guide students in career development.
- Owned by: `Community` module (mentorship context)
- Entity type: Entity

### Mentorship
A formal relationship between a Student and a Mentor.
- Owned by: `Community` module
- Entity type: Aggregate Root

---

## O

### Opportunity
Any career-relevant opening available to students: jobs, internships, scholarships, competitions.
- Owned by: `Opportunities` module
- Entity type: Aggregate Root

### Opportunity Application
A student's formal expression of interest in an Opportunity.
- Owned by: `Opportunities` module
- Entity type: Entity (child of Opportunity or Student)

---

## P

### Portfolio
A curated collection of a student's projects, achievements, and work samples.
- Owned by: `CareerProfile` module
- Entity type: Entity (child of CareerProfile aggregate)

### Portfolio Item
A single entry in a Portfolio (project, certificate, achievement, link).
- Owned by: `CareerProfile` module
- Entity type: Entity

---

## R

### Recommendation
An AI or advisor-generated suggestion for a student action (course, skill, opportunity).
- Owned by: `Guidance` module
- Entity type: Entity

---

## S

### Schedule
A student's organized timetable of classes, tasks, and events.
- Owned by: `Productivity` module
- Entity type: Aggregate Root

### Scholarship
A financial award offered to students based on merit or need.
- Owned by: `Opportunities` module
- Entity type: Entity (subtype of Opportunity)

### Skill
A specific ability or competency a student possesses or is developing.
- Owned by: `Skills` module
- Entity type: Value Object or Entity depending on context

### Skill Profile
The aggregated set of skills, competencies, and certificates for a student.
- Owned by: `Skills` module
- Entity type: Aggregate Root

### Student
The primary actor of the platform — an individual enrolled at a university.
- Owned by: `Shared` module (identity) + all modules (behavior)
- Entity type: Aggregate Root in `Shared`; referenced by ID in other modules

### Study Plan
See: Academic Plan

---

## T

### Task
A discrete unit of work a student needs to complete.
- Owned by: `Productivity` module
- Entity type: Entity (child of Schedule or standalone)

---

## U

### University
An educational institution that subscribes to the SSP platform.
- Owned by: `Administration` module
- Entity type: Aggregate Root (Tenant)

### User
The authentication and identity record for any person using the platform.
- Owned by: `Shared` module
- Entity type: Aggregate Root
- Note: `User` ≠ `Student`. A User becomes a Student when enrolled. An advisor is also a User.

---

## Naming Anti-Patterns (Forbidden)

| Forbidden Term | Use Instead        |
|----------------|--------------------|
| `Profile`      | `StudentProfile`, `CareerProfile`, `SkillProfile` |
| `Record`       | Be specific: `GradeRecord`, `AttendanceRecord` |
| `Data`         | Be specific: `StudentData` → `StudentDto`      |
| `Info`         | Be specific: `CourseInfo` → `CourseDto`        |
| `Item`         | Qualify: `PortfolioItem`, `TaskItem`           |
| `Manager`      | Use specific domain term or Service            |
