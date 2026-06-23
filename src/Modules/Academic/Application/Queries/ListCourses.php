<?php

declare(strict_types=1);

namespace Modules\Academic\Application\Queries;

use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;

final readonly class ListCourses
{
    public function __construct(
        private CourseRepositoryInterface $courses,
        private AcademicMapper $mapper,
    ) {}

    public function execute(int $page = 1, int $perPage = 15): array
    {
        $courses = $this->courses->findAllActivePaginated($page, $perPage);

        return [
            'data' => array_map(
                fn ($course) => $this->mapper->toCourseDto($course),
                $courses->items()
            ),
            'pagination' => [
                'current_page' => $courses->currentPage(),
                'per_page' => $courses->perPage(),
                'total' => $courses->total(),
                'last_page' => $courses->lastPage(),
                'from' => $courses->firstItem(),
                'to' => $courses->lastItem(),
            ],
        ];
    }
}
