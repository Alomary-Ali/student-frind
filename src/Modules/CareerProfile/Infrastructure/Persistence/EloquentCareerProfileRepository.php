<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\CareerProfile\Domain\Entities\PortfolioItem;
use Modules\CareerProfile\Domain\Entities\Experience;
use Modules\CareerProfile\Domain\Entities\Resume;
use Modules\CareerProfile\Domain\Entities\CareerGoal;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentCareerProfile;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentPortfolioItem;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentExperience;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentResume;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentCareerGoal;

final class EloquentCareerProfileRepository implements CareerProfileRepositoryInterface
{
    public function findById(CareerProfileId $id): ?CareerProfile
    {
        $model = EloquentCareerProfile::with(['portfolioItems', 'experiences', 'resumes', 'careerGoals'])
            ->find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(StudentId $studentId): ?CareerProfile
    {
        $model = EloquentCareerProfile::with(['portfolioItems', 'experiences', 'resumes', 'careerGoals'])
            ->where('student_id', $studentId->value())
            ->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(CareerProfile $profile): void
    {
        $model = EloquentCareerProfile::find($profile->id()->value());

        if ($model === null) {
            $model = new EloquentCareerProfile();
            $model->id = $profile->id()->value();
        }

        $model->student_id = $profile->studentId()->value();
        $model->major = $profile->major();
        $model->summary = $profile->summary();
        $model->interests = $profile->interests();
        $model->languages = $profile->languages();
        $model->save();

        // Sync Portfolio Items
        $currentItemIds = array_map(fn(PortfolioItem $item) => $item->id()->value(), $profile->portfolioItems());
        EloquentPortfolioItem::where('career_profile_id', $profile->id()->value())
            ->whereNotIn('id', $currentItemIds)
            ->delete();

        foreach ($profile->portfolioItems() as $item) {
            $itemModel = EloquentPortfolioItem::find($item->id()->value()) ?? new EloquentPortfolioItem();
            $itemModel->id = $item->id()->value();
            $itemModel->career_profile_id = $profile->id()->value();
            $itemModel->title = $item->title();
            $itemModel->description = $item->description();
            $itemModel->project_url = $item->projectUrl();
            $itemModel->github_url = $item->githubUrl();
            $itemModel->start_date = $item->startDate()->format('Y-m-d H:i:s');
            $itemModel->end_date = $item->endDate()?->format('Y-m-d H:i:s');
            $itemModel->technologies = $item->technologies();
            $itemModel->save();
        }

        // Sync Experiences
        $currentExperienceIds = array_map(fn(Experience $exp) => $exp->id()->value(), $profile->experiences());
        EloquentExperience::where('career_profile_id', $profile->id()->value())
            ->whereNotIn('id', $currentExperienceIds)
            ->delete();

        foreach ($profile->experiences() as $exp) {
            $expModel = EloquentExperience::find($exp->id()->value()) ?? new EloquentExperience();
            $expModel->id = $exp->id()->value();
            $expModel->career_profile_id = $profile->id()->value();
            $expModel->company = $exp->company();
            $expModel->position = $exp->position();
            $expModel->description = $exp->description();
            $expModel->start_date = $exp->startDate()->format('Y-m-d H:i:s');
            $expModel->end_date = $exp->endDate()?->format('Y-m-d H:i:s');
            $expModel->is_current = $exp->isCurrent();
            $expModel->save();
        }

        // Sync Resumes
        $currentResumeIds = array_map(fn(Resume $res) => $res->id()->value(), $profile->resumes());
        EloquentResume::where('career_profile_id', $profile->id()->value())
            ->whereNotIn('id', $currentResumeIds)
            ->delete();

        foreach ($profile->resumes() as $res) {
            $resModel = EloquentResume::find($res->id()->value()) ?? new EloquentResume();
            $resModel->id = $res->id()->value();
            $resModel->career_profile_id = $profile->id()->value();
            $resModel->template = $res->template()->value;
            $resModel->content = $res->content();
            $resModel->generated_at = $res->generatedAt()->format('Y-m-d H:i:s');
            $resModel->save();
        }

        // Sync Career Goals
        $currentGoalIds = array_map(fn(CareerGoal $goal) => $goal->id()->value(), $profile->careerGoals());
        EloquentCareerGoal::where('career_profile_id', $profile->id()->value())
            ->whereNotIn('id', $currentGoalIds)
            ->delete();

        foreach ($profile->careerGoals() as $goal) {
            $goalModel = EloquentCareerGoal::find($goal->id()->value()) ?? new EloquentCareerGoal();
            $goalModel->id = $goal->id()->value();
            $goalModel->career_profile_id = $profile->id()->value();
            $goalModel->title = $goal->title();
            $goalModel->target_date = $goal->targetDate()->format('Y-m-d H:i:s');
            $goalModel->status = $goal->status()->value;
            $goalModel->progress = $goal->progress();
            $goalModel->save();
        }
    }

    public function delete(CareerProfileId $id): void
    {
        EloquentCareerProfile::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentCareerProfile $model): CareerProfile
    {
        $portfolioItems = [];
        foreach ($model->portfolioItems as $item) {
            $portfolioItems[] = PortfolioItem::reconstitute(
                id: PortfolioItemId::of($item->id),
                careerProfileId: CareerProfileId::of($item->career_profile_id),
                title: $item->title,
                description: $item->description,
                projectUrl: $item->project_url,
                githubUrl: $item->github_url,
                startDate: new DateTimeImmutable($item->start_date->format('Y-m-d H:i:s')),
                endDate: $item->end_date ? new DateTimeImmutable($item->end_date->format('Y-m-d H:i:s')) : null,
                technologies: $item->technologies ?? []
            );
        }

        $experiences = [];
        foreach ($model->experiences as $exp) {
            $experiences[] = Experience::reconstitute(
                id: ExperienceId::of($exp->id),
                careerProfileId: CareerProfileId::of($exp->career_profile_id),
                company: $exp->company,
                position: $exp->position,
                description: $exp->description,
                startDate: new DateTimeImmutable($exp->start_date->format('Y-m-d H:i:s')),
                endDate: $exp->end_date ? new DateTimeImmutable($exp->end_date->format('Y-m-d H:i:s')) : null,
                isCurrent: (bool) $exp->is_current
            );
        }

        $resumes = [];
        foreach ($model->resumes as $res) {
            $resumes[] = Resume::reconstitute(
                id: ResumeId::of($res->id),
                careerProfileId: CareerProfileId::of($res->career_profile_id),
                template: ResumeTemplate::from($res->template),
                content: $res->content,
                generatedAt: new DateTimeImmutable($res->generated_at->format('Y-m-d H:i:s'))
            );
        }

        $careerGoals = [];
        foreach ($model->careerGoals as $goal) {
            $careerGoals[] = CareerGoal::reconstitute(
                id: CareerGoalId::of($goal->id),
                careerProfileId: CareerProfileId::of($goal->career_profile_id),
                title: $goal->title,
                targetDate: new DateTimeImmutable($goal->target_date->format('Y-m-d H:i:s')),
                status: GoalStatus::from($goal->status),
                progress: (int) $goal->progress
            );
        }

        return CareerProfile::reconstitute(
            id: CareerProfileId::of($model->id),
            studentId: StudentId::of($model->student_id),
            major: $model->major,
            summary: $model->summary ?? '',
            interests: $model->interests ?? [],
            languages: $model->languages ?? [],
            portfolioItems: $portfolioItems,
            experiences: $experiences,
            resumes: $resumes,
            careerGoals: $careerGoals,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s'))
        );
    }
}
