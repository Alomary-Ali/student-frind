# Productivity Module — Domain Events

---

## GoalCreated

| Field | Value |
|-------|-------|
| **Purpose** | Notify other modules that a goal was created |
| **Trigger** | `Goal::create()` factory method |
| **Payload** | `goalId`, `userId`, `title`, `targetDate`, `priority`, `occurredAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Analytics (goal tracking), Guidance (productivity insights) |

---

## GoalCompleted

| Field | Value |
|-------|-------|
| **Purpose** | Notify that a goal has been achieved |
| **Trigger** | `Goal::complete()` when progress reaches 100% |
| **Payload** | `goalId`, `userId`, `title`, `completedAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Analytics (achievement tracking), Guidance (celebration) |

---

## TaskCreated

| Field | Value |
|-------|-------|
| **Purpose** | Notify that a new task was created |
| **Trigger** | `Task::create()` factory method |
| **Payload** | `taskId`, `userId`, `title`, `dueDate`, `priority`, `linkedGoalId`, `occurredAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Analytics (task tracking) |

---

## TaskCompleted

| Field | Value |
|-------|-------|
| **Purpose** | Notify that a task has been completed |
| **Trigger** | `Task::complete()` method |
| **Payload** | `taskId`, `userId`, `title`, `linkedGoalId`, `completedAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Analytics (completion tracking), Goal progress update |

---

## ReminderCreated

| Field | Value |
|-------|-------|
| **Purpose** | Notify that a reminder was created |
| **Trigger** | `Reminder::create()` factory method |
| **Payload** | `reminderId`, `userId`, `message`, `triggerAt`, `type`, `linkedTaskId`, `occurredAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Notification Service |

---

## ReminderTriggered

| Field | Value |
|-------|-------|
| **Purpose** | Notify that a reminder was triggered |
| **Trigger** | `Reminder::trigger()` method |
| **Payload** | `reminderId`, `userId`, `message`, `type`, `triggeredAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Notification Service |

---

## ProductivitySnapshotGenerated

| Field | Value |
|-------|-------|
| **Purpose** | Notify that a productivity snapshot was generated |
| **Trigger** | `ProductivitySnapshot::create()` factory method |
| **Payload** | `snapshotId`, `userId`, `totalGoals`, `completedGoals`, `totalTasks`, `completedTasks`, `overdueTasks`, `completionRate`, `snapshotDate`, `occurredAt` |
| **Consumers** | None (current) |
| **Future Consumers** | Analytics (productivity trends), Guidance (insights) |
