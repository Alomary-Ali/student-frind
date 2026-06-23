# Productivity Module

## Purpose

Helps students organize their time and achieve goals through task management, goal setting, exam tracking, assignment management, project management, calendar events, reminders, and productivity analytics. Acts as the "daily operations center" for the student.

---

## Domain Model

### Aggregate Roots
- `Goal` — academic or personal goal with progress tracking
- `Task` — daily/actionable item, optionally linked to a Goal
- `Reminder` — time-based notification
- `CalendarEvent` — scheduled event (lecture, deadline, personal)
- `ProductivitySnapshot` — period snapshot for analytics
- `Project` — multi-stage project with phases and team members
- `Exam` — academic exam with type, date, readiness status
- `Assignment` — academic assignment with status and grade

### Value Objects
- `GoalId`, `GoalProgress`, `TaskId`, `ReminderId`, `CalendarEventId`, `ProductivitySnapshotId`
- `PriorityLevel` (low, medium, high, urgent)
- `ProjectId`, `ExamId`, `AssignmentId` (old-style)

### Enums
- `GoalStatus`, `GoalType` — lifecycle and categorization of goals
- `TaskStatus` (pending, in_progress, completed, postponed, cancelled)
- `TaskPriority` (low, medium, high, urgent)
- `ReminderStatus`, `ReminderType`
- `NotificationType` — 8 types with Arabic labels and urgency flags
- `EventType` — 6 calendar event categories
- `ProjectStatus`, `ExamType`, `AssignmentStatus`
- `ReadinessStatus` (not_ready, needs_review, partially_ready, fully_ready)

---

## Use Cases

| Use Case | Type | Status |
|----------|------|--------|
| `CreateGoal` | Command | ✅ |
| `UpdateGoalProgress` | Command | ✅ |
| `CreateTask` | Command | ✅ |
| `CompleteTask` | Command | ✅ |
| `CreateReminder` | Command | ✅ |
| `CreateCalendarEvent` | Command | ✅ |
| `GenerateProductivitySnapshot` | Command | ✅ |
| `GetProductivityDashboard` | Query | ✅ |
| `CreateProject` | Command (old) | ✅ |
| `UpdateProjectProgress` | Command (old) | ✅ |
| `CreateExam` | Command (old) | ✅ |
| `UpdateExamStatus` | Command (old) | ✅ |
| `CreateAssignment` | Command (old) | ✅ |
| `UpdateAssignmentProgress` | Command (old) | ✅ |

---

## Domain Events Published

- `GoalCreated`, `GoalCompleted`
- `TaskCreated`, `TaskCompleted`
- `ReminderCreated`, `ReminderTriggered`
- `ProductivitySnapshotGenerated`
- `ProjectCreated`, `ExamCreated`, `AssignmentCreated`

---

## Public Contracts (Exposed to Other Modules)

None (publishes events only; listens to Academic `StudentEnrolled` and `CourseCompleted`)

---

## Dependencies

- **Shared** — User identity, EventDispatcher
- **Academic** — Listens to `StudentEnrolled`, `CourseCompleted` events

---

## API Base Path

`/api/v1/productivity`

See `Docs/api-endpoints.md` for full documentation.

---

## Web Routes

| Route | Controller | Description |
|-------|-----------|-------------|
| `/productivity/dashboard` | `ProductivityDashboardController` | لوحة الإنتاجية مع إحصائيات ورسوم بيانية |
| `/productivity/goals` | `ProductivityGoalController@index` | قائمة الأهداف |
| `/productivity/goals/{id}` | `ProductivityGoalController@show` | تفاصيل الهدف |
| `/productivity/tasks` | `ProductivityTaskController@index` | قائمة المهام |
| `/productivity/tasks/{id}` | `ProductivityTaskController@show` | تفاصيل المهمة |
| `/productivity/tasks/{id}/complete` | `ProductivityTaskController@complete` | إكمال المهمة |
| `/productivity/calendar` | `ProductivityCalendarController@index` | التقويم |
| `/productivity/reminders` | `ProductivityReminderController@index` | التذكيرات |
| `/productivity/assignments` | `AssignmentController@index` | الواجبات |
| `/productivity/exams` | `ExamController@index` | الاختبارات |
| `/productivity/projects` | `ProjectController@index` | المشاريع |

---

## Status

Phase: **Phase 2 — Academic Core**  
Implementation: **Active** — 11 web views, 14 API endpoints, 8 domain entities, 3 domain services, 7 test files
