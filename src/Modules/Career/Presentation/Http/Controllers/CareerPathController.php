<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Career\Application\UseCases\ExploreCareerPaths;
use Modules\Career\Application\UseCases\GetCareerPathDetails;
use Modules\Career\Application\UseCases\RecommendCareerPath;

final readonly class CareerPathController
{
    public function __construct(
        private ExploreCareerPaths $exploreCareerPaths,
        private GetCareerPathDetails $getCareerPathDetails,
        private RecommendCareerPath $recommendCareerPath,
    ) {}

    public function index(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $paths = $this->exploreCareerPaths->execute($studentId);

        return view('career.paths.index', [
            'paths' => $paths,
        ]);
    }

    public function show(string $id): View
    {
        $path = $this->getCareerPathDetails->execute($id);

        return view('career.paths.show', [
            'path' => $path,
        ]);
    }

    public function recommendations(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $recommendations = $this->recommendCareerPath->execute($studentId);

        return view('career.paths.recommendations', [
            'recommendations' => $recommendations,
        ]);
    }

    private function resolveStudentId(Request $request): ?string
    {
        $user = $request->user();
        if (! $user) {
            return null;
        }

        $student = EloquentStudent::where('user_id', $user->id)->first();

        return $student?->id;
    }
}
