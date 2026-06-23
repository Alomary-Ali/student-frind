<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Application\Queries\ListCourses;
use Modules\Academic\Presentation\Responses\ApiResponse;

final class ListCoursesController extends Controller
{
    public function __construct(
        private readonly ListCourses $query,
    ) {}

    public function __invoke(Request $request): View|JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 15);

        $result = $this->query->execute($page, $perPage);

        // If API request (expects JSON), return JSON response
        if ($request->expectsJson()) {
            return ApiResponse::success(data: [
                'courses' => array_map(fn ($c) => [
                    'id' => $c->id,
                    'code' => $c->code,
                    'title' => $c->title,
                    'description' => $c->description,
                    'credit_hours' => $c->creditHours,
                    'is_active' => $c->isActive,
                ], $result['data']),
                'pagination' => $result['pagination'],
            ]);
        }

        // Return view for web requests
        return view('academic.courses', [
            'courses' => collect($result['data']),
        ]);
    }
}
