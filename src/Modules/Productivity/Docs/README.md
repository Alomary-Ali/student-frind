# Productivity Module

## Purpose

The Productivity Module helps students manage their university life, commitments, academic responsibilities, goals, and personal productivity. It provides tools for goal setting, task management, reminders, calendar events, and productivity tracking.

## Domain Model

### Entities

- **Goal**: Personal or academic objectives a student sets and tracks (Aggregate Root)
- **Task**: Discrete units of work a student needs to complete
- **Reminder**: Notifications for tasks and events
- **CalendarEvent**: Calendar events and appointments
- **ProductivitySnapshot**: Historical productivity metrics

### Value Objects

- **GoalId**: UUID identifier for goals
- **TaskId**: UUID identifier for tasks
- **ReminderId**: UUID identifier for reminders
- **CalendarEventId**: UUID identifier for calendar events
- **ProductivitySnapshotId**: UUID identifier for snapshots
- **PriorityLevel**: Priority levels (low, medium, high, urgent)
- **GoalProgress**: Progress percentage (0-100)

### Enums

- **GoalStatus**: Active, Completed, Archived
- **TaskStatus**: Pending, InProgress, Completed, Cancelled
- **ReminderStatus**: Pending, Triggered, Dismissed
- **ReminderType**: Email, Push, InApp

## Use Cases

- CreateGoal: Create a new goal
- UpdateGoalProgress: Update goal progress
- CreateTask: Create a new task
- CompleteTask: Mark task as completed
- CreateReminder: Create a new reminder
- CreateCalendarEvent: Create a calendar event
- GenerateProductivitySnapshot: Generate productivity metrics
- GetProductivityDashboard: Get productivity dashboard data

## Academic Module Integration

The Productivity Module integrates with the Academic Module through Domain Events:

- **StudentEnrolled**: Creates a task to review enrollment and a reminder
- **CourseCompleted**: Creates a task to update academic progress

## API Endpoints

See `api-endpoints.md` for detailed API documentation.

## Database Design

See `database-design.md` for detailed database schema.

## Domain Events

See `domain-events.md` for detailed domain event documentation.
