<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\Skills\Application\UseCases\GetOrCreateSkillProfile;
use Modules\Skills\Application\UseCases\AddSkill;
use Modules\Skills\Application\UseCases\AddCertification;
use Modules\Skills\Domain\Contracts\AchievementRepositoryInterface;
use Modules\Skills\Domain\Contracts\LearningPathRepositoryInterface;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;

final readonly class SkillsController
{
    public function __construct(
        private GetOrCreateSkillProfile $getOrCreateSkillProfile,
        private AddSkill $addSkill,
        private AddCertification $addCertification,
        private AchievementRepositoryInterface $achievements,
        private LearningPathRepositoryInterface $learningPaths,
        private SkillsMapper $mapper,
    ) {
    }

    public function index(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $profile = null;
        $achievementDtos = [];
        $learningPathDtos = [];

        if ($studentId) {
            $profile = $this->getOrCreateSkillProfile->execute($studentId);
            $sid = StudentId::of($studentId);
            $achievementDtos = array_map(
                fn($a) => $this->mapper->toAchievementDto($a),
                $this->achievements->findByStudentId($sid)
            );
            $learningPathDtos = array_map(
                fn($p) => $this->mapper->toLearningPathDto($p),
                $this->learningPaths->findByStudentId($sid)
            );
        }

        return view('skills.index', [
            'profile'       => $profile,
            'achievements'  => $achievementDtos,
            'learningPaths' => $learningPathDtos,
        ]);
    }

    public function storeSkill(Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (!$studentId) {
            return redirect()->route('skills.index')->with('error', 'لم يتم العثور على ملف المهارات');
        }

        try {
            $this->addSkill->execute($studentId, $request->all());
            return redirect()->route('skills.index')->with('success', 'تمت إضافة المهارة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة المهارة')->withInput();
        }
    }

    public function storeCertification(Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (!$studentId) {
            return redirect()->route('skills.index')->with('error', 'لم يتم العثور على ملف المهارات');
        }

        try {
            $this->addCertification->execute($studentId, $request->all());
            return redirect()->route('skills.index')->with('success', 'تمت إضافة الشهادة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الشهادة')->withInput();
        }
    }

    private function resolveStudentId(Request $request): ?string
    {
        $user = $request->user();
        if (!$user) {
            return null;
        }

        $student = EloquentStudent::where('user_id', $user->id)->first();
        return $student?->id;
    }
}
