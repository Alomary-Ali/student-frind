<?php

declare(strict_types=1);

namespace Modules\Productivity\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Academic\Domain\Events\CourseCompleted;
use Modules\Academic\Domain\Events\StudentEnrolled;
use Modules\Productivity\Listeners\HandleAcademicCourseCompleted;
use Modules\Productivity\Listeners\HandleAcademicEnrollment;

final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StudentEnrolled::class => [
            HandleAcademicEnrollment::class,
        ],
        CourseCompleted::class => [
            HandleAcademicCourseCompleted::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
