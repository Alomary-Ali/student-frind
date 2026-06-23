<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Career\Application\UseCases\GetPublicPortfolio;
use Modules\Career\Application\UseCases\IncrementPortfolioViews;
use Modules\Career\Application\UseCases\PublishPortfolio;
use Modules\Career\Presentation\Http\Requests\PublishPortfolioRequest;

final readonly class PortfolioController
{
    public function __construct(
        private PublishPortfolio $publishPortfolio,
        private GetPublicPortfolio $getPublicPortfolio,
        private IncrementPortfolioViews $incrementPortfolioViews,
    ) {}

    public function edit(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $portfolio = $studentId ? $this->getPublicPortfolio->findByStudent($studentId) : null;

        return view('career.portfolio.edit', [
            'portfolio' => $portfolio,
        ]);
    }

    public function update(PublishPortfolioRequest $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        $this->publishPortfolio->execute(
            studentId: $studentId,
            data: $request->validated(),
        );

        return redirect()->back()->with('success', 'تم تحديث الملف التعريفي بنجاح');
    }

    public function show(string $slug): View
    {
        $portfolio = $this->getPublicPortfolio->execute($slug);

        if (! $portfolio || ! $portfolio->isPublished) {
            abort(404);
        }

        $this->incrementPortfolioViews->execute($slug);

        return view('career.portfolio.public', [
            'portfolio' => $portfolio,
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
