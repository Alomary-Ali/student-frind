<?php

declare(strict_types=1);

namespace Modules\Academic;

use Illuminate\Support\ServiceProvider;
use Modules\Academic\Domain\Contracts\AcademicAlertRepositoryInterface;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\AcademicPlanReaderInterface;
use Modules\Academic\Domain\Contracts\AcademicPlanRepositoryInterface;
use Modules\Academic\Domain\Contracts\AcademicRecordRepositoryInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\CurriculumRepositoryInterface;
use Modules\Academic\Domain\Contracts\EnrollmentRepositoryInterface;
use Modules\Academic\Domain\Contracts\GraduationPathRepositoryInterface;
use Modules\Academic\Domain\Contracts\SemesterRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Contracts\TransactionManagerInterface;
use Modules\Academic\Domain\Services\AcademicAlertService;
use Modules\Academic\Domain\Services\GpaCalculationService;
use Modules\Academic\Domain\Services\PrerequisiteValidationService;
use Modules\Academic\Infrastructure\Audit\DatabaseAcademicAuditLogger;
use Modules\Academic\Infrastructure\Integrations\LaravelTransactionManager;
use Modules\Academic\Infrastructure\Repositories\AcademicPlanReader;
use Modules\Academic\Infrastructure\Repositories\EloquentAcademicAlertRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentAcademicPlanRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentAcademicRecordRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentCourseRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentCurriculumRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentEnrollmentRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentGraduationPathRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentSemesterRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentStudentRepository;

final class AcademicServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GpaCalculationService::class);
        $this->app->singleton(PrerequisiteValidationService::class);
        $this->app->singleton(AcademicAlertService::class);

        $bindings = [
            StudentRepositoryInterface::class => EloquentStudentRepository::class,
            CourseRepositoryInterface::class => EloquentCourseRepository::class,
            SemesterRepositoryInterface::class => EloquentSemesterRepository::class,
            CurriculumRepositoryInterface::class => EloquentCurriculumRepository::class,
            AcademicPlanRepositoryInterface::class => EloquentAcademicPlanRepository::class,
            EnrollmentRepositoryInterface::class => EloquentEnrollmentRepository::class,
            AcademicRecordRepositoryInterface::class => EloquentAcademicRecordRepository::class,
            GraduationPathRepositoryInterface::class => EloquentGraduationPathRepository::class,
            AcademicAlertRepositoryInterface::class => EloquentAcademicAlertRepository::class,
            AcademicPlanReaderInterface::class => AcademicPlanReader::class,
            TransactionManagerInterface::class => LaravelTransactionManager::class,
            AcademicAuditLoggerInterface::class => DatabaseAcademicAuditLogger::class,
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Presentation/Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/Infrastructure/Persistence/Migrations');
    }
}
