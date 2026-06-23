<?php

declare(strict_types=1);

namespace Modules\Opportunities\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Opportunities\Application\Mappers\OpportunityMapper;
use Modules\Opportunities\Application\UseCases\ApplyToOpportunity;
use Modules\Opportunities\Application\UseCases\CreateOpportunity;
use Modules\Opportunities\Application\UseCases\DeleteOpportunity;
use Modules\Opportunities\Application\UseCases\GenerateRecommendations;
use Modules\Opportunities\Application\UseCases\GetRecommendedOpportunities;
use Modules\Opportunities\Application\UseCases\SaveOpportunity;
use Modules\Opportunities\Application\UseCases\TrackApplication;
use Modules\Opportunities\Application\UseCases\UpdateOpportunity;
use Modules\Opportunities\Domain\Contracts\ApplicationRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\OpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\SavedOpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Enums\OpportunityType;

final readonly class OpportunitiesController
{
    public function __construct(
        private CreateOpportunity $createOpportunity,
        private UpdateOpportunity $updateOpportunity,
        private DeleteOpportunity $deleteOpportunity,
        private SaveOpportunity $saveOpportunity,
        private ApplyToOpportunity $applyToOpportunity,
        private TrackApplication $trackApplication,
        private GenerateRecommendations $generateRecommendations,
        private GetRecommendedOpportunities $getRecommendedOpportunities,
        private OpportunityRepositoryInterface $opportunities,
        private ApplicationRepositoryInterface $applications,
        private SavedOpportunityRepositoryInterface $saved,
        private OpportunityMapper $mapper,
    ) {}

    public function index(): View
    {
        $allOpportunities = $this->opportunities->findAll();
        $opportunities = array_map(fn ($opp) => $this->mapper->toOpportunityDto($opp), $allOpportunities);

        $jobs = array_filter($opportunities, fn ($dto) => $dto->type === OpportunityType::JOB->value);
        $internships = array_filter($opportunities, fn ($dto) => $dto->type === OpportunityType::INTERNSHIP->value);
        $scholarships = array_filter($opportunities, fn ($dto) => $dto->type === OpportunityType::SCHOLARSHIP->value);
        $courses = array_filter($opportunities, fn ($dto) => $dto->type === OpportunityType::COURSE->value);
        $competitions = array_filter($opportunities, fn ($dto) => $dto->type === OpportunityType::COMPETITION->value);
        $volunteering = array_filter($opportunities, fn ($dto) => $dto->type === OpportunityType::VOLUNTEERING->value);
        $conferences = array_filter($opportunities, fn ($dto) => $dto->type === OpportunityType::CONFERENCE->value);

        return view('opportunities.index', compact(
            'opportunities', 'jobs', 'internships', 'scholarships',
            'courses', 'competitions', 'volunteering', 'conferences',
        ));
    }

    public function recommended(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);

        if ($studentId === null) {
            return view('opportunities.recommended', ['recommendations' => []]);
        }

        $recommendations = $this->getRecommendedOpportunities->execute($studentId);

        return view('opportunities.recommended', compact('recommendations'));
    }

    public function saved(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);

        if ($studentId === null) {
            return view('opportunities.saved', ['opportunities' => []]);
        }

        $savedList = $this->saved->findByStudentId($studentId);
        $opportunityIds = array_map(fn ($saved) => $saved->opportunityId(), $savedList);
        $opps = [];
        foreach ($opportunityIds as $oppId) {
            $opp = $this->opportunities->findById($oppId);
            if ($opp !== null) {
                $opps[] = $this->mapper->toOpportunityDto($opp);
            }
        }

        return view('opportunities.saved', ['opportunities' => $opps]);
    }

    public function applications(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);

        if ($studentId === null) {
            return view('opportunities.applications', ['applications' => []]);
        }

        $apps = $this->applications->findByStudentId($studentId);
        $applications = array_map(fn ($app) => $this->mapper->toApplicationDto($app), $apps);

        return view('opportunities.applications', compact('applications'));
    }

    public function scholarships(): View
    {
        $all = $this->opportunities->findByType(OpportunityType::SCHOLARSHIP);
        $opportunities = array_map(fn ($opp) => $this->mapper->toOpportunityDto($opp), $all);

        return view('opportunities.index', compact('opportunities'));
    }

    public function jobs(): View
    {
        $all = $this->opportunities->findByType(OpportunityType::JOB);
        $opportunities = array_map(fn ($opp) => $this->mapper->toOpportunityDto($opp), $all);

        return view('opportunities.index', compact('opportunities'));
    }

    public function internships(): View
    {
        $all = $this->opportunities->findByType(OpportunityType::INTERNSHIP);
        $opportunities = array_map(fn ($opp) => $this->mapper->toOpportunityDto($opp), $all);

        return view('opportunities.index', compact('opportunities'));
    }

    public function courses(): View
    {
        $all = $this->opportunities->findByType(OpportunityType::COURSE);
        $opportunities = array_map(fn ($opp) => $this->mapper->toOpportunityDto($opp), $all);

        return view('opportunities.index', compact('opportunities'));
    }

    public function competitions(): View
    {
        $all = $this->opportunities->findByType(OpportunityType::COMPETITION);
        $opportunities = array_map(fn ($opp) => $this->mapper->toOpportunityDto($opp), $all);

        return view('opportunities.index', compact('opportunities'));
    }

    public function save(Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if ($studentId === null) {
            return redirect()->route('login');
        }

        $this->saveOpportunity->execute(
            studentId: $studentId,
            opportunityId: $request->input('opportunity_id'),
        );

        return redirect()->back()->with('success', 'تم حفظ الفرصة بنجاح');
    }

    public function apply(Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if ($studentId === null) {
            return redirect()->route('login');
        }

        $this->applyToOpportunity->execute(
            studentId: $studentId,
            opportunityId: $request->input('opportunity_id'),
            notes: $request->input('notes'),
        );

        return redirect()->back()->with('success', 'تم التقديم على الفرصة بنجاح');
    }

    private function resolveStudentId(Request $request): ?string
    {
        $user = $request->user();

        if ($user === null) {
            return null;
        }

        $student = EloquentStudent::where('user_id', $user->id)->first();

        return $student?->id;
    }
}
