<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Presentation\Http\Requests;

use Modules\Productivity\Presentation\Http\Requests\CreateAssignmentRequest;
use Modules\Productivity\Presentation\Http\Requests\CreateCalendarEventRequest;
use Modules\Productivity\Presentation\Http\Requests\CreateExamRequest;
use Modules\Productivity\Presentation\Http\Requests\CreateGoalRequest;
use Modules\Productivity\Presentation\Http\Requests\CreateProjectRequest;
use Modules\Productivity\Presentation\Http\Requests\CreateReminderRequest;
use Modules\Productivity\Presentation\Http\Requests\CreateTaskRequest;
use PHPUnit\Framework\TestCase;

final class ProductivityRequestsTest extends TestCase
{
    public function test_create_goal_request_rules(): void
    {
        $request = new CreateGoalRequest;
        $rules = $request->rules();

        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('target_date', $rules);
        $this->assertArrayHasKey('priority', $rules);

        $this->assertStringContainsString('required', $rules['user_id']);
        $this->assertStringContainsString('uuid', $rules['user_id']);
        $this->assertStringContainsString('max:255', $rules['title']);
        $this->assertStringContainsString('after:today', $rules['target_date']);
        $this->assertStringContainsString('in:low,medium,high,urgent', $rules['priority']);
    }

    public function test_create_goal_request_authorize(): void
    {
        $request = new CreateGoalRequest;
        $this->assertTrue($request->authorize());
    }

    public function test_create_task_request_rules(): void
    {
        $request = new CreateTaskRequest;
        $rules = $request->rules();

        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('due_date', $rules);
        $this->assertArrayHasKey('priority', $rules);
        $this->assertArrayHasKey('linked_goal_id', $rules);

        $this->assertStringContainsString('required', $rules['title']);
        $this->assertStringContainsString('nullable|date', $rules['due_date']);
        $this->assertStringContainsString('in:low,medium,high,urgent', $rules['priority']);
        $this->assertStringContainsString('nullable|uuid', $rules['linked_goal_id']);
    }

    public function test_create_reminder_request_rules(): void
    {
        $request = new CreateReminderRequest;
        $rules = $request->rules();

        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('message', $rules);
        $this->assertArrayHasKey('trigger_at', $rules);
        $this->assertArrayHasKey('type', $rules);
        $this->assertArrayHasKey('linked_task_id', $rules);

        $this->assertStringContainsString('max:500', $rules['message']);
        $this->assertStringContainsString('after:now', $rules['trigger_at']);
        $this->assertStringContainsString('in:email,push,in_app', $rules['type']);
    }

    public function test_create_calendar_event_request_rules(): void
    {
        $request = new CreateCalendarEventRequest;
        $rules = $request->rules();

        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('starts_at', $rules);
        $this->assertArrayHasKey('ends_at', $rules);
        $this->assertArrayHasKey('is_all_day', $rules);
        $this->assertArrayHasKey('linked_task_id', $rules);

        $this->assertStringContainsString('boolean', $rules['is_all_day']);
        $this->assertStringContainsString('after:starts_at', $rules['ends_at']);
    }

    public function test_create_assignment_request_rules(): void
    {
        $request = new CreateAssignmentRequest;
        $rules = $request->rules();

        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('course_id', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('due_date', $rules);

        $this->assertStringContainsString('exists:users,id', $rules['user_id']);
        $this->assertStringContainsString('after:now', $rules['due_date']);
        $this->assertStringContainsString('nullable|string', $rules['description']);
    }

    public function test_create_exam_request_rules(): void
    {
        $request = new CreateExamRequest;
        $rules = $request->rules();

        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('course_id', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('exam_type', $rules);
        $this->assertArrayHasKey('exam_date', $rules);
        $this->assertArrayHasKey('location', $rules);

        $this->assertStringContainsString('in:midterm,final,quiz,practical,oral', $rules['exam_type']);
        $this->assertStringContainsString('after:now', $rules['exam_date']);
        $this->assertStringContainsString('max:255', $rules['location']);
    }

    public function test_create_project_request_rules(): void
    {
        $request = new CreateProjectRequest;
        $rules = $request->rules();

        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('start_date', $rules);
        $this->assertArrayHasKey('due_date', $rules);

        $this->assertStringContainsString('after_or_equal:today', $rules['start_date']);
        $this->assertStringContainsString('after:start_date', $rules['due_date']);
    }
}
