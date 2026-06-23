# خطة تنفيذ الوحدة الثالثة: Career Development & Skills Hub
## التطوير المهني والمهارات الذكية

**التاريخ:** 20 يونيو 2026  
**الحالة:** Completed ✅  
**نسبة الإنجاز:** 100%

---

## الهدف

تحويل رفيق من مجرد نظام أكاديمي إلى منصة تساعد الطالب على:
- اكتشاف مهاراته
- بناء ملفه المهني
- تحليل نقاط القوة والضعف
- تطوير المهارات المطلوبة لسوق العمل
- إنشاء سيرة ذاتية احترافية
- متابعة التقدم المهني
- الاستعداد للتوظيف

---

## المشاكل التي تحلها هذه الوحدة

حالياً الطالب يعرف:
- ✅ معدله (GPA)
- ✅ خطته الدراسية
- ✅ مهامه (Tasks)

لكن لا يعرف:
- ❌ هل تخصصه مطلوب؟
- ❌ ما المهارات الناقصة؟
- ❌ هل جاهز لسوق العمل؟
- ❌ كيف يبني CV؟
- ❌ كيف يجهز LinkedIn؟
- ❌ ماذا يتعلم بعد ذلك؟

---

## المكونات الرئيسية

### 1. Career Profile (الملف المهني الذكي)
**الموقع:** `src/Modules/CareerProfile/`

يحتوي على:
- التخصص (Major)
- المهارات (Skills)
- الاهتمامات (Interests)
- الشهادات (Certifications)
- المشاريع (Projects)
- الخبرات (Experience)
- اللغات (Languages)
- الإنجازات (Achievements)

### 2. Skills Management (إدارة المهارات)
**الموقع:** `src/Modules/Skills/`

تصنيف المهارات:
- **Technical Skills:**
  - Programming
  - Networking
  - Design
  - AI
  - Data Analysis
- **Soft Skills:**
  - Leadership
  - Communication
  - Teamwork
  - Problem Solving
  - Time Management

### 3. Skill Gap Analysis (محرك تحليل الفجوات)
يقارن بين:
- Current Skills VS Market Required Skills

ويولد:
- Skill Gap Report

**مثال:**
```
Frontend Developer
Current: HTML, CSS
Missing: JavaScript, React, Git, API Integration
```

### 4. CV Builder (منشئ السيرة الذاتية)
إنشاء CV تلقائياً من بيانات النظام.

**القوالب:**
- ATS Friendly
- Modern
- Academic
- Professional

**التصدير:**
- PDF
- DOCX

### 5. LinkedIn Optimizer (تحليل الملف المهني)
فحص:
- العنوان
- الوصف
- المهارات
- المشاريع

ويعطي:
- LinkedIn Score (0-100)

### 6. Learning Roadmaps (خرائط التعلم)
**مثال:**
```
Backend Developer Roadmap:
PHP → Laravel → API → Testing → Docker → CI/CD → Cloud
```

### 7. Career Readiness Score (أحد أهم محركات رفيق)
يقيس:
- GPA
- Projects
- Skills
- Certifications
- Experience
- Activity

ويولد:
- Career Score (0-100)

### 8. Achievement System (نظام الإنجازات)
مثل الألعاب.

**أمثلة:**
- First Project
- 10 Tasks Completed
- First Certificate
- 100 Study Hours
- Career Ready

---

## Domain Layer

### Entities
**الموقع:** `src/Modules/CareerProfile/Domain/Entities/` و `src/Modules/Skills/Domain/Entities/`

#### CareerProfile Module:
1. **CareerProfile** (Aggregate Root)
   - `studentId: StudentId`
   - `major: string`
   - `summary: string`
   - `interests: array<string>`
   - `languages: array<Language>`
   - `createdAt: DateTimeImmutable`
   - `updatedAt: DateTimeImmutable`

2. **PortfolioItem**
   - `id: PortfolioItemId`
   - `careerProfileId: CareerProfileId`
   - `title: string`
   - `description: string`
   - `projectUrl: string|null`
   - `githubUrl: string|null`
   - `startDate: DateTimeImmutable`
   - `endDate: DateTimeImmutable|null`
   - `technologies: array<string>`

3. **Experience**
   - `id: ExperienceId`
   - `careerProfileId: CareerProfileId`
   - `company: string`
   - `position: string`
   - `description: string`
   - `startDate: DateTimeImmutable`
   - `endDate: DateTimeImmutable|null`
   - `isCurrent: bool`

4. **Resume**
   - `id: ResumeId`
   - `careerProfileId: CareerProfileId`
   - `template: ResumeTemplate`
   - `content: string`
   - `generatedAt: DateTimeImmutable`

5. **CareerGoal**
   - `id: CareerGoalId`
   - `careerProfileId: CareerProfileId`
   - `title: string`
   - `targetDate: DateTimeImmutable`
   - `status: GoalStatus`
   - `progress: int (0-100)`

#### Skills Module:
1. **SkillProfile** (Aggregate Root)
   - `studentId: StudentId`
   - `skills: array<Skill>`
   - `certifications: array<Certification>`
   - `createdAt: DateTimeImmutable`
   - `updatedAt: DateTimeImmutable`

2. **Skill**
   - `id: SkillId`
   - `name: string`
   - `category: SkillCategory`
   - `level: SkillLevel`
   - `yearsOfExperience: int`
   - `lastUsed: DateTimeImmutable`

3. **Certification**
   - `id: CertificationId`
   - `name: string`
   - `issuer: string`
   - `issueDate: DateTimeImmutable`
   - `expiryDate: DateTimeImmutable|null`
   - `credentialUrl: string|null`
   - `verificationCode: string|null`

4. **Achievement**
   - `id: AchievementId`
   - `studentId: StudentId`
   - `type: AchievementType`
   - `title: string`
   - `description: string`
   - `unlockedAt: DateTimeImmutable`
   - `badgeUrl: string|null`

5. **LearningPath**
   - `id: LearningPathId`
   - `studentId: StudentId`
   - `title: string`
   - `targetRole: string`
   - `steps: array<LearningStep>`
   - `progress: int (0-100)`
   - `estimatedCompletionDate: DateTimeImmutable|null`

### Value Objects
**الموقع:** `src/Modules/CareerProfile/Domain/ValueObjects/` و `src/Modules/Skills/Domain/ValueObjects/`

#### CareerProfile Module:
1. **CareerProfileId**
2. **PortfolioItemId**
3. **ExperienceId**
4. **ResumeId**
5. **CareerGoalId**
6. **CareerScore** (0-100)

#### Skills Module:
1. **SkillId**
2. **CertificationId**
3. **AchievementId**
4. **LearningPathId**

### Enums
**الموقع:** `src/Modules/CareerProfile/Domain/Enums/` و `src/Modules/Skills/Domain/Enums/`

#### CareerProfile Module:
1. **ResumeTemplate**
   - ATS_FRIENDLY
   - MODERN
   - ACADEMIC
   - PROFESSIONAL

2. **GoalStatus**
   - NOT_STARTED
   - IN_PROGRESS
   - COMPLETED
   - POSTPONED
   - CANCELLED

#### Skills Module:
1. **SkillCategory**
   - PROGRAMMING
   - NETWORKING
   - DESIGN
   - AI
   - DATA_ANALYSIS
   - LEADERSHIP
   - COMMUNICATION
   - TEAMWORK
   - PROBLEM_SOLVING
   - TIME_MANAGEMENT

2. **SkillLevel**
   - BEGINNER
   - INTERMEDIATE
   - ADVANCED
   - EXPERT

3. **AchievementType**
   - ACADEMIC
   - PRODUCTIVITY
   - CAREER
   - COMMUNITY

### Domain Events
**الموقع:** `src/Modules/CareerProfile/Domain/Events/` و `src/Modules/Skills/Domain/Events/`

#### CareerProfile Module:
1. **CareerProfileCreated**
2. **PortfolioItemAdded**
3. **ExperienceAdded**
4. **ResumeGenerated**
5. **CareerGoalCreated**
6. **CareerGoalCompleted**

#### Skills Module:
1. **SkillAdded**
2. **SkillLevelUpdated**
3. **CertificationEarned**
4. **AchievementUnlocked**
5. **LearningPathCreated**
6. **LearningPathCompleted**

### Domain Services
**الموقع:** `src/Modules/CareerProfile/Domain/Services/` و `src/Modules/Skills/Domain/Services/`

#### CareerProfile Module:
1. **CareerScoreCalculator**
   - يحسب Career Score بناءً على:
     - 25% Skills
     - 20% GPA
     - 20% Projects
     - 15% Certifications
     - 10% Experience
     - 10% Activities

2. **ResumeGenerator**
   - يولد CV تلقائياً من بيانات النظام
   - يدعم قوالب متعددة

3. **LinkedInOptimizer**
   - يحلل الملف المهني
   - يعطي LinkedIn Score

#### Skills Module:
1. **SkillGapAnalyzer**
   - يقارن Current Skills مع Market Required Skills
   - يولد Skill Gap Report

2. **LearningPathGenerator**
   - يولد خطة تعلم بناءً على الهدف المهني
   - يحدد الخطوات المطلوبة

3. **AchievementUnlocker**
   - يتحقق من تحقيق الإنجازات
   - يفتح الإنجازات تلقائياً

---

## Application Layer

### Use Cases
**الموقع:** `src/Modules/CareerProfile/Application/UseCases/` و `src/Modules/Skills/Application/UseCases/`

#### CareerProfile Module:
1. **CreateCareerProfile**
   - Input: `CreateCareerProfileDto`
   - Output: `CareerProfileDto`

2. **UpdateCareerProfile**
   - Input: `UpdateCareerProfileDto`
   - Output: `CareerProfileDto`

3. **AddPortfolioItem**
   - Input: `AddPortfolioItemDto`
   - Output: `PortfolioItemDto`

4. **AddExperience**
   - Input: `AddExperienceDto`
   - Output: `ExperienceDto`

5. **GenerateResume**
   - Input: `GenerateResumeDto`
   - Output: `ResumeDto`

6. **CreateCareerGoal**
   - Input: `CreateCareerGoalDto`
   - Output: `CareerGoalDto`

7. **UpdateCareerGoalProgress**
   - Input: `UpdateCareerGoalProgressDto`
   - Output: `CareerGoalDto`

8. **GetCareerDashboard**
   - Input: `GetCareerDashboardDto`
   - Output: `CareerDashboardDto`

9. **CalculateCareerScore**
   - Input: `CalculateCareerScoreDto`
   - Output: `CareerScore`

10. **OptimizeLinkedIn**
    - Input: `OptimizeLinkedInDto`
    - Output: `LinkedInOptimizationReportDto`

#### Skills Module:
1. **CreateSkillProfile**
   - Input: `CreateSkillProfileDto`
   - Output: `SkillProfileDto`

2. **AddSkill**
   - Input: `AddSkillDto`
   - Output: `SkillDto`

3. **UpdateSkillLevel**
   - Input: `UpdateSkillLevelDto`
   - Output: `SkillDto`

4. **AddCertification**
   - Input: `AddCertificationDto`
   - Output: `CertificationDto`

5. **AnalyzeSkillGap**
   - Input: `AnalyzeSkillGapDto`
   - Output: `SkillGapReportDto`

6. **GenerateLearningRoadmap**
   - Input: `GenerateLearningRoadmapDto`
   - Output: `LearningRoadmapDto`

7. **UnlockAchievement**
   - Input: `UnlockAchievementDto`
   - Output: `AchievementDto`

8. **GetStudentAchievements**
   - Input: `GetStudentAchievementsDto`
   - Output: `array<AchievementDto>`

9. **CreateLearningPath**
   - Input: `CreateLearningPathDto`
   - Output: `LearningPathDto`

10. **UpdateLearningPathProgress**
    - Input: `UpdateLearningPathProgressDto`
    - Output: `LearningPathDto`

### DTOs
**الموقع:** `src/Modules/CareerProfile/Application/DTOs/` و `src/Modules/Skills/Application/DTOs/`

#### CareerProfile Module:
1. **CareerProfileDto**
2. **CreateCareerProfileDto**
3. **UpdateCareerProfileDto**
4. **PortfolioItemDto**
5. **AddPortfolioItemDto**
6. **ExperienceDto**
7. **AddExperienceDto**
8. **ResumeDto**
9. **GenerateResumeDto**
10. **CareerGoalDto**
11. **CreateCareerGoalDto**
12. **UpdateCareerGoalProgressDto**
13. **CareerDashboardDto**
14. **GetCareerDashboardDto**
15. **CareerScore**
16. **LinkedInOptimizationReportDto**
17. **OptimizeLinkedInDto**

#### Skills Module:
1. **SkillProfileDto**
2. **CreateSkillProfileDto**
3. **SkillDto**
4. **AddSkillDto**
5. **UpdateSkillLevelDto**
6. **CertificationDto**
7. **AddCertificationDto**
8. **SkillGapReportDto**
9. **AnalyzeSkillGapDto**
10. **LearningRoadmapDto**
11. **GenerateLearningRoadmapDto**
12. **AchievementDto**
13. **UnlockAchievementDto**
14. **GetStudentAchievementsDto**
15. **LearningPathDto**
16. **CreateLearningPathDto**
17. **UpdateLearningPathProgressDto`

### Mappers
**الموقع:** `src/Modules/CareerProfile/Application/Mappers/` و `src/Modules/Skills/Application/Mappers/`

#### CareerProfile Module:
1. **CareerProfileMapper**
   - `toCareerProfileDto(CareerProfile $profile): CareerProfileDto`
   - `toPortfolioItemDto(PortfolioItem $item): PortfolioItemDto`
   - `toExperienceDto(Experience $experience): ExperienceDto`
   - `toResumeDto(Resume $resume): ResumeDto`
   - `toCareerGoalDto(CareerGoal $goal): CareerGoalDto`

#### Skills Module:
1. **SkillsMapper**
   - `toSkillProfileDto(SkillProfile $profile): SkillProfileDto`
   - `toSkillDto(Skill $skill): SkillDto`
   - `toCertificationDto(Certification $certification): CertificationDto`
   - `toAchievementDto(Achievement $achievement): AchievementDto`
   - `toLearningPathDto(LearningPath $path): LearningPathDto`

---

## Infrastructure Layer

### Eloquent Models
**الموقع:** `src/Modules/CareerProfile/Infrastructure/Persistence/` و `src/Modules/Skills/Infrastructure/Persistence/`

#### CareerProfile Module:
1. **EloquentCareerProfile**
2. **EloquentPortfolioItem**
3. **EloquentExperience**
4. **EloquentResume**
5. **EloquentCareerGoal**

#### Skills Module:
1. **EloquentSkillProfile**
2. **EloquentSkill**
3. **EloquentCertification**
4. **EloquentAchievement**
5. **EloquentLearningPath**

### Repositories
**الموقع:** `src/Modules/CareerProfile/Infrastructure/Repositories/` و `src/Modules/Skills/Infrastructure/Repositories/`

#### CareerProfile Module:
1. **CareerProfileRepositoryInterface** (Domain/Contracts/)
2. **EloquentCareerProfileRepository** (Infrastructure/Repositories/)
3. **PortfolioItemRepositoryInterface** (Domain/Contracts/)
4. **EloquentPortfolioItemRepository** (Infrastructure/Repositories/)
5. **ExperienceRepositoryInterface** (Domain/Contracts/)
6. **EloquentExperienceRepository** (Infrastructure/Repositories/)
7. **ResumeRepositoryInterface** (Domain/Contracts/)
8. **EloquentResumeRepository** (Infrastructure/Repositories/)
9. **CareerGoalRepositoryInterface** (Domain/Contracts/)
10. **EloquentCareerGoalRepository** (Infrastructure/Repositories/)

#### Skills Module:
1. **SkillProfileRepositoryInterface** (Domain/Contracts/)
2. **EloquentSkillProfileRepository** (Infrastructure/Repositories/)
3. **SkillRepositoryInterface** (Domain/Contracts/)
4. **EloquentSkillRepository** (Infrastructure/Repositories/)
5. **CertificationRepositoryInterface** (Domain/Contracts/)
6. **EloquentCertificationRepository** (Infrastructure/Repositories/)
7. **AchievementRepositoryInterface** (Domain/Contracts/)
8. **EloquentAchievementRepository** (Infrastructure/Repositories/)
9. **LearningPathRepositoryInterface** (Domain/Contracts/)
10. **EloquentLearningPathRepository** (Infrastructure/Repositories/)

### Migrations
**الموقع:** `database/migrations/`

#### CareerProfile Module:
1. **create_career_profiles_table**
   - `id (UUID, primary)`
   - `student_id (UUID, foreign → students.id)`
   - `major (string)`
   - `summary (text)`
   - `interests (json)`
   - `languages (json)`
   - `created_at (timestamp)`
   - `updated_at (timestamp)`

2. **create_portfolio_items_table**
   - `id (UUID, primary)`
   - `career_profile_id (UUID, foreign → career_profiles.id)`
   - `title (string)`
   - `description (text)`
   - `project_url (string, nullable)`
   - `github_url (string, nullable)`
   - `start_date (date)`
   - `end_date (date, nullable)`
   - `technologies (json)`
   - `created_at (timestamp)`
   - `updated_at (timestamp)`

3. **create_experiences_table**
   - `id (UUID, primary)`
   - `career_profile_id (UUID, foreign → career_profiles.id)`
   - `company (string)`
   - `position (string)`
   - `description (text)`
   - `start_date (date)`
   - `end_date (date, nullable)`
   - `is_current (boolean)`
   - `created_at (timestamp)`
   - `updated_at (timestamp)`

4. **create_resumes_table**
   - `id (UUID, primary)`
   - `career_profile_id (UUID, foreign → career_profiles.id)`
   - `template (enum: ats_friendly, modern, academic, professional)`
   - `content (longText)`
   - `generated_at (timestamp)`
   - `created_at (timestamp)`
   - `updated_at (timestamp)`

5. **create_career_goals_table**
   - `id (UUID, primary)`
   - `career_profile_id (UUID, foreign → career_profiles.id)`
   - `title (string)`
   - `target_date (date)`
   - `status (enum: not_started, in_progress, completed, postponed, cancelled)`
   - `progress (int, default 0)`
   - `created_at (timestamp)`
   - `updated_at (timestamp)`

#### Skills Module:
1. **create_skill_profiles_table**
   - `id (UUID, primary)`
   - `student_id (UUID, foreign → students.id)`
   - `created_at (timestamp)`
   - `updated_at (timestamp)`

2. **create_skills_table**
   - `id (UUID, primary)`
   - `skill_profile_id (UUID, foreign → skill_profiles.id)`
   - `name (string)`
   - `category (enum: programming, networking, design, ai, data_analysis, leadership, communication, teamwork, problem_solving, time_management)`
   - `level (enum: beginner, intermediate, advanced, expert)`
   - `years_of_experience (int, default 0)`
   - `last_used (date)`
   - `created_at (timestamp)`
   - `updated_at (timestamp)`

3. **create_certifications_table**
   - `id (UUID, primary)`
   - `skill_profile_id (UUID, foreign → skill_profiles.id)`
   - `name (string)`
   - `issuer (string)`
   - `issue_date (date)`
   - `expiry_date (date, nullable)`
   - `credential_url (string, nullable)`
   - `verification_code (string, nullable)`
   - `created_at (timestamp)`
   - `updated_at (timestamp)`

4. **create_achievements_table**
   - `id (UUID, primary)`
   - `student_id (UUID, foreign → students.id)`
   - `type (enum: academic, productivity, career, community)`
   - `title (string)`
   - `description (text)`
   - `badge_url (string, nullable)`
   - `unlocked_at (timestamp)`
   - `created_at (timestamp)`

5. **create_learning_paths_table**
   - `id (UUID, primary)`
   - `student_id (UUID, foreign → students.id)`
   - `title (string)`
   - `target_role (string)`
   - `steps (json)`
   - `progress (int, default 0)`
   - `estimated_completion_date (date, nullable)`
   - `created_at (timestamp)`
   - `updated_at (timestamp)`

### Seeders
**الموقع:** `database/seeders/`

#### CareerProfile Module:
1. **CareerProfileSeeder**
   - بيانات تجريبية للـ Career Profiles

#### Skills Module:
1. **SkillsSeeder**
   - بيانات تجريبية للـ Skills
2. **AchievementSeeder**
   - تعريف الإنجازات الممكنة

---

## Presentation Layer

### Controllers
**الموقع:** `src/Modules/CareerProfile/Presentation/Controllers/` و `src/Modules/Skills/Presentation/Controllers/`

#### CareerProfile Module:
1. **CreateCareerProfileController**
2. **UpdateCareerProfileController**
3. **AddPortfolioItemController**
4. **AddExperienceController**
5. **GenerateResumeController**
6. **CreateCareerGoalController**
7. **UpdateCareerGoalProgressController**
8. **CareerDashboardController**
9. **CalculateCareerScoreController**
10. **OptimizeLinkedInController**

#### Skills Module:
1. **CreateSkillProfileController**
2. **AddSkillController**
3. **UpdateSkillLevelController**
4. **AddCertificationController**
5. **AnalyzeSkillGapController**
6. **GenerateLearningRoadmapController**
7. **UnlockAchievementController**
8. **GetStudentAchievementsController**
9. **CreateLearningPathController**
10. **UpdateLearningPathProgressController**

### Form Requests
**الموقع:** `src/Modules/CareerProfile/Presentation/Requests/` و `src/Modules/Skills/Presentation/Requests/`

#### CareerProfile Module:
1. **CreateCareerProfileRequest**
2. **UpdateCareerProfileRequest**
3. **AddPortfolioItemRequest**
4. **AddExperienceRequest**
5. **GenerateResumeRequest**
6. **CreateCareerGoalRequest**
7. **UpdateCareerGoalProgressRequest**

#### Skills Module:
1. **CreateSkillProfileRequest**
2. **AddSkillRequest**
3. **UpdateSkillLevelRequest**
4. **AddCertificationRequest**
5. **AnalyzeSkillGapRequest**
6. **GenerateLearningRoadmapRequest**
7. **UnlockAchievementRequest**
8. **CreateLearningPathRequest**
9. **UpdateLearningPathProgressRequest**

### API Resources
**الموقع:** `src/Modules/CareerProfile/Presentation/Resources/` و `src/Modules/Skills/Presentation/Resources/`

#### CareerProfile Module:
1. **CareerProfileResource**
2. **PortfolioItemResource**
3. **ExperienceResource**
4. **ResumeResource**
5. **CareerGoalResource**
6. **CareerDashboardResource**
7. **CareerScoreResource**
8. **LinkedInOptimizationReportResource**

#### Skills Module:
1. **SkillProfileResource**
2. **SkillResource**
3. **CertificationResource**
4. **SkillGapReportResource**
5. **LearningRoadmapResource**
6. **AchievementResource**
7. **LearningPathResource**

### Views
**الموقع:** `resources/views/career/` و `resources/views/skills/`

#### CareerProfile Module:
1. **dashboard.blade.php**
   - Career Score
   - Skills Overview
   - Certifications
   - Resume Status
   - Achievements
   - Recommendations

2. **profile.blade.php**
   - Career Profile details
   - Edit form

3. **portfolio.blade.php**
   - Portfolio items list
   - Add new item form

4. **experience.blade.php**
   - Experience list
   - Add new experience form

5. **resume.blade.php**
   - Resume generator
   - Template selection
   - Preview and download

6. **goals.blade.php**
   - Career goals list
   - Add new goal form
   - Progress tracking

#### Skills Module:
1. **skills.blade.php**
   - Skills list
   - Skill levels
   - Add new skill form

2. **certifications.blade.php**
   - Certifications list
   - Add new certification form

3. **skill-gap.blade.php**
   - Skill Gap Analysis report
   - Missing skills
   - Recommendations

4. **roadmap.blade.php**
   - Learning roadmap
   - Progress tracking
   - Steps completion

5. **achievements.blade.php**
   - Achievements list
   - Badges display
   - Unlocked achievements

### Routes
**الموقع:** `routes/web.php` و `routes/api.php`

#### Web Routes:
```php
// Career Profile Routes
Route::middleware(['auth', 'role:student,advisor'])->group(function () {
    Route::get('/career/dashboard', [CareerDashboardController::class, '__invoke'])->name('career.dashboard');
    Route::get('/career/profile', [CareerProfileController::class, 'show'])->name('career.profile.show');
    Route::post('/career/profile', [UpdateCareerProfileController::class, '__invoke'])->name('career.profile.update');
    Route::get('/career/portfolio', [PortfolioController::class, 'index'])->name('career.portfolio.index');
    Route::post('/career/portfolio', [AddPortfolioItemController::class, '__invoke'])->name('career.portfolio.add');
    Route::get('/career/experience', [ExperienceController::class, 'index'])->name('career.experience.index');
    Route::post('/career/experience', [AddExperienceController::class, '__invoke'])->name('career.experience.add');
    Route::get('/career/resume', [ResumeController::class, 'index'])->name('career.resume.index');
    Route::post('/career/resume', [GenerateResumeController::class, '__invoke'])->name('career.resume.generate');
    Route::get('/career/goals', [CareerGoalController::class, 'index'])->name('career.goals.index');
    Route::post('/career/goals', [CreateCareerGoalController::class, '__invoke'])->name('career.goals.create');
    Route::post('/career/goals/{id}/progress', [UpdateCareerGoalProgressController::class, '__invoke'])->name('career.goals.progress');
});

// Skills Routes
Route::middleware(['auth', 'role:student,advisor'])->group(function () {
    Route::get('/skills', [SkillsController::class, 'index'])->name('skills.index');
    Route::post('/skills', [AddSkillController::class, '__invoke'])->name('skills.add');
    Route::post('/skills/{id}/level', [UpdateSkillLevelController::class, '__invoke'])->name('skills.level.update');
    Route::get('/skills/certifications', [CertificationsController::class, 'index'])->name('skills.certifications.index');
    Route::post('/skills/certifications', [AddCertificationController::class, '__invoke'])->name('skills.certifications.add');
    Route::get('/skills/gap-analysis', [SkillGapAnalysisController::class, 'index'])->name('skills.gap-analysis');
    Route::post('/skills/gap-analysis', [AnalyzeSkillGapController::class, '__invoke'])->name('skills.gap-analysis.analyze');
    Route::get('/skills/roadmap', [LearningRoadmapController::class, 'index'])->name('skills.roadmap');
    Route::post('/skills/roadmap', [GenerateLearningRoadmapController::class, '__invoke'])->name('skills.roadmap.generate');
    Route::get('/skills/achievements', [AchievementsController::class, 'index'])->name('skills.achievements');
});
```

#### API Routes:
```php
// Career Profile API Routes
Route::middleware(['auth:api', 'role:student,advisor'])->prefix('api/v1/career')->group(function () {
    Route::get('/dashboard', [CareerDashboardController::class, '__invoke']);
    Route::post('/profile', [CreateCareerProfileController::class, '__invoke']);
    Route::put('/profile/{id}', [UpdateCareerProfileController::class, '__invoke']);
    Route::post('/portfolio', [AddPortfolioItemController::class, '__invoke']);
    Route::post('/experience', [AddExperienceController::class, '__invoke']);
    Route::post('/resume', [GenerateResumeController::class, '__invoke']);
    Route::post('/goals', [CreateCareerGoalController::class, '__invoke']);
    Route::post('/goals/{id}/progress', [UpdateCareerGoalProgressController::class, '__invoke']);
    Route::get('/score', [CalculateCareerScoreController::class, '__invoke']);
    Route::post('/linkedin/optimize', [OptimizeLinkedInController::class, '__invoke']);
});

// Skills API Routes
Route::middleware(['auth:api', 'role:student,advisor'])->prefix('api/v1/skills')->group(function () {
    Route::post('/profile', [CreateSkillProfileController::class, '__invoke']);
    Route::post('/skills', [AddSkillController::class, '__invoke']);
    Route::put('/skills/{id}/level', [UpdateSkillLevelController::class, '__invoke']);
    Route::post('/certifications', [AddCertificationController::class, '__invoke']);
    Route::post('/gap-analysis', [AnalyzeSkillGapController::class, '__invoke']);
    Route::post('/roadmap', [GenerateLearningRoadmapController::class, '__invoke']);
    Route::post('/achievements/unlock', [UnlockAchievementController::class, '__invoke']);
    Route::get('/achievements', [GetStudentAchievementsController::class, '__invoke']);
    Route::post('/learning-paths', [CreateLearningPathController::class, '__invoke']);
    Route::put('/learning-paths/{id}/progress', [UpdateLearningPathProgressController::class, '__invoke']);
});
```

---

## Tests

### Unit Tests
**الموقع:** `src/Modules/CareerProfile/Tests/Unit/` و `src/Modules/Skills/Tests/Unit/`

#### CareerProfile Module:
1. **CareerProfileTest**
   - `test_career_profile_can_be_created`
   - `test_career_profile_can_be_updated`
   - `test_portfolio_item_can_be_added`
   - `test_experience_can_be_added`
   - `test_career_goal_can_be_created`
   - `test_career_goal_progress_can_be_updated`

2. **CareerScoreCalculatorTest**
   - `test_career_score_is_calculated_correctly`
   - `test_career_score_weights_are_applied_correctly`

3. **ResumeGeneratorTest**
   - `test_resume_can_be_generated_from_profile`
   - `test_resume_template_is_applied_correctly`

#### Skills Module:
1. **SkillProfileTest**
   - `test_skill_profile_can_be_created`
   - `test_skill_can_be_added`
   - `test_skill_level_can_be_updated`
   - `test_certification_can_be_added`

2. **SkillGapAnalyzerTest**
   - `test_skill_gap_is_analyzed_correctly`
   - `test_missing_skills_are_identified`

3. **LearningPathGeneratorTest**
   - `test_learning_path_is_generated_correctly`
   - `test_learning_steps_are_ordered_correctly`

4. **AchievementUnlockerTest**
   - `test_achievement_is_unlocked_when_criteria_met`
   - `test_achievement_is_not_unlocked_when_criteria_not_met`

### Feature Tests
**الموقع:** `src/Modules/CareerProfile/Tests/Feature/` و `src/Modules/Skills/Tests/Feature/`

#### CareerProfile Module:
1. **CreateCareerProfileTest**
2. **UpdateCareerProfileTest**
3. **AddPortfolioItemTest**
4. **AddExperienceTest**
5. **GenerateResumeTest**
6. **CreateCareerGoalTest**
7. **CareerDashboardTest**
8. **CalculateCareerScoreTest**
9. **OptimizeLinkedInTest**

#### Skills Module:
1. **CreateSkillProfileTest**
2. **AddSkillTest**
3. **UpdateSkillLevelTest**
4. **AddCertificationTest**
5. **AnalyzeSkillGapTest**
6. **GenerateLearningRoadmapTest**
7. **UnlockAchievementTest**
8. **GetStudentAchievementsTest**
9. **CreateLearningPathTest**

### Integration Tests
**الموقع:** `src/Modules/CareerProfile/Tests/Integration/` و `src/Modules/Skills/Tests/Integration/`

#### CareerProfile Module:
1. **CareerProfileModuleIntegrationTest**
   - اختبار التكامل بين Use Cases و Repositories
   - اختبار Domain Events

#### Skills Module:
1. **SkillsModuleIntegrationTest**
   - اختبار التكامل بين Use Cases و Repositories
   - اختبار Domain Events
   - اختبار التكامل مع Academic Module (CourseCompleted event)

---

## Integration مع الوحدات السابقة

### من الوحدة الأولى (Academic Module):
جلب:
- GPA (المعدل)
- الساعات المنجزة (Completed Credit Hours)
- الإنجازات الأكاديمية (Academic Achievements)
- المقررات المكتملة (Completed Courses)

**الطريقة:**
- الاستماع إلى Domain Events من Academic Module:
  - `CourseCompleted` → تحديث المهارات المكتسبة
  - `GradeUpdated` → تحديث GPA في Career Score

### من الوحدة الثانية (Productivity Module):
جلب:
- الإنتاجية (Productivity Metrics)
- المشاريع (Projects)
- المهام المكتملة (Completed Tasks)
- الأهداف (Goals)

**الطريقة:**
- الاستماع إلى Domain Events من Productivity Module:
  - `TaskCompleted` → تحديث Career Score
  - `ProjectCompleted` → إضافة إلى Portfolio
  - `GoalCompleted` → فتح Achievement

---

## مراحل التنفيذ

### المرحلة 1: Domain Layer (الأساس)
**الأولوية:** Critical
**المدة المتوقعة:** 3-4 أيام

1.1 إنشاء Entities
- CareerProfile, PortfolioItem, Experience, Resume, CareerGoal
- SkillProfile, Skill, Certification, Achievement, LearningPath

1.2 إنشاء Value Objects
- CareerProfileId, PortfolioItemId, ExperienceId, ResumeId, CareerGoalId, CareerScore
- SkillId, CertificationId, AchievementId, LearningPathId

1.3 إنشاء Enums
- ResumeTemplate, GoalStatus
- SkillCategory, SkillLevel, AchievementType

1.4 إنشاء Domain Events
- CareerProfileCreated, PortfolioItemAdded, ExperienceAdded, ResumeGenerated, CareerGoalCreated, CareerGoalCompleted
- SkillAdded, SkillLevelUpdated, CertificationEarned, AchievementUnlocked, LearningPathCreated, LearningPathCompleted

1.5 إنشاء Domain Services
- CareerScoreCalculator, ResumeGenerator, LinkedInOptimizer
- SkillGapAnalyzer, LearningPathGenerator, AchievementUnlocker

### المرحلة 2: Application Layer (منطق التطبيق)
**الأولوية:** Critical
**المدة المتوقعة:** 3-4 أيام

2.1 إنشاء Use Cases
- CreateCareerProfile, UpdateCareerProfile, AddPortfolioItem, AddExperience, GenerateResume, CreateCareerGoal, UpdateCareerGoalProgress, GetCareerDashboard, CalculateCareerScore, OptimizeLinkedIn
- CreateSkillProfile, AddSkill, UpdateSkillLevel, AddCertification, AnalyzeSkillGap, GenerateLearningRoadmap, UnlockAchievement, GetStudentAchievements, CreateLearningPath, UpdateLearningPathProgress

2.2 إنشاء DTOs
- جميع DTOs المطلوبة لكل Use Case

2.3 إنشاء Mappers
- CareerProfileMapper, SkillsMapper

### المرحلة 3: Infrastructure Layer (البنية التحتية)
**الأولوية:** Critical
**المدة المتوقعة:** 3-4 أيام

3.1 إنشاء Eloquent Models
- جميع Models المطلوبة

3.2 إنشاء Repositories
- جميع Repository Interfaces و Implementations

3.3 إنشاء Migrations
- جميع Migrations المطلوبة

3.4 إنشاء Seeders
- CareerProfileSeeder, SkillsSeeder, AchievementSeeder

3.5 تسجيل Services في ServiceProviders
- CareerProfileServiceProvider, SkillsServiceProvider

### المرحلة 4: Presentation Layer (واجهة المستخدم)
**الأولوية:** High
**المدة المتوقعة:** 4-5 أيام

4.1 إنشاء Controllers
- جميع Controllers المطلوبة

4.2 إنشاء Form Requests
- جميع Form Requests المطلوبة

4.3 إنشاء API Resources
- جميع API Resources المطلوبة

4.4 إنشاء Views
- dashboard.blade.php, profile.blade.php, portfolio.blade.php, experience.blade.php, resume.blade.php, goals.blade.php
- skills.blade.php, certifications.blade.php, skill-gap.blade.php, roadmap.blade.php, achievements.blade.php

4.5 إضافة Routes
- Web Routes و API Routes

### المرحلة 5: Integration مع الوحدات السابقة
**الأولوية:** High
**المدة المتوقعة:** 2-3 أيام

5.1 تكامل مع Academic Module
- الاستماع إلى CourseCompleted event
- الاستماع إلى GradeUpdated event
- جلب GPA و Completed Credit Hours

5.2 تكامل مع Productivity Module
- الاستماع إلى TaskCompleted event
- الاستماع إلى ProjectCompleted event
- الاستماع إلى GoalCompleted event

### المرحلة 6: Tests (الاختبارات)
**الأولوية:** High
**المدة المتوقعة:** 3-4 أيام

6.1 Unit Tests
- جميع Unit Tests المطلوبة

6.2 Feature Tests
- جميع Feature Tests المطلوبة

6.3 Integration Tests
- جميع Integration Tests المطلوبة

### المرحلة 7: Documentation و Code Review
**الأولوية:** Medium
**المدة المتوقعة:** 1-2 يوم

7.1 تحديث Documentation
- تحديث READMEs
- تحديث AGENTS.md
- تحديث completed-work.md

7.2 Code Review
- مراجعة جميع الكود للتأكد من الالتزام بقواعد المشروع
- مراجعة DDD rules
- مراجعة Clean Architecture

---

## القواعد التي يجب الالتزام بها

### 1. PHP Coding Standards
- `declare(strict_types=1);` في بداية كل ملف PHP
- `final class` لجميع Use Cases و Controllers
- `readonly properties` على جميع DTOs
- Full type hints (parameters + return types)
- Constructor injection only (لا `new Foo()` في business code)

### 2. Naming Conventions
- Entity: PascalCase noun (CareerProfile, Skill)
- Value Object: PascalCase noun (CareerProfileId, SkillId)
- Domain Event: PascalCase past tense (CareerProfileCreated, SkillAdded)
- Use Case: Verb + Noun (CreateCareerProfile, AddSkill)
- DTO: Noun + Dto (CareerProfileDto, SkillDto)
- Repository Interface: I + Noun + Repository (ICareerProfileRepository)
- Repository Implementation: Noun + Repository (EloquentCareerProfileRepository)
- Controller: Noun + Controller (CareerProfileController)
- Method: camelCase, verb-first (calculateCareerScore(), addSkill())
- Boolean variables: prefixed with is/has/can/should ($isCompleted, $hasSkill)

### 3. Layer Rules
- **Domain Layer:** Pure PHP فقط، NO Illuminate imports، NO Eloquent، NO Facades، NO HTTP/Cache/Queue
- **Application Layer:** One Use Case = one operation، inject repository interfaces، return DTOs
- **Infrastructure Layer:** Implement Domain Contracts، Eloquent models stay here، map Eloquent ↔ Domain
- **Presentation Layer:** Validate via Form Requests، call ONE Use Case per endpoint، return API Resource

### 4. Controller Template
```php
final class CreateCareerProfileController extends Controller
{
    public function __construct(
        private readonly CreateCareerProfile $useCase,
    ) {}

    public function __invoke(CreateCareerProfileRequest $request): JsonResponse
    {
        $dto = CreateCareerProfileDto::fromRequest($request);
        $result = $this->useCase->execute($dto);
        return CareerProfileResource::make($result)->response()->setStatusCode(201);
    }
}
```

### 5. Value Object Template
```php
final class CareerProfileId
{
    private function __construct(private readonly string $value) {}
    
    public static function of(string $value): self
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidCareerProfileIdException($value);
        }
        return new self($value);
    }
    
    public function value(): string { return $this->value; }
    public function equals(self $other): bool { return $this->value === $other->value; }
}
```

### 6. Testing Standards
- كل Use Case يجب أن يكون له Unit Test
- كل Controller endpoint يجب أن يكون له Feature Test
- تسمية الاختبارات: test_[describes_behavior] (PHPUnit)
- استخدام factories لبيانات الاختبار — لا raw arrays
- Coverage targets: Domain 90%+, Application 85%+, Infrastructure 70%+, Presentation 70%+

### 7. No Mock Data Policy
- كل feature يجب أن يعمل على REAL database data عبر Use Cases و Repositories
- ZERO mock data، hardcoded arrays، أو faker-generated content في production code
- المسموح: Config seeders (lookup tables)، Test seeders (APP_ENV=testing only)

### 8. Provider-Agnostic Architecture
- جميع external service integrations (PDF generation, LinkedIn API, etc.) يجب أن تكون abstracted behind Domain Contracts
- Provider implementations live exclusively in Infrastructure/Providers/
- Provider names must NEVER appear in Domain, Application, or Presentation layers
- Use Cases depend on interfaces, not concrete implementations

### 9. Module Communication Rules
- **ALLOWED:** Domain Events، Contract Interfaces من Domain/Contracts/، Application Services
- **FORBIDDEN:** Direct Entity imports، cross-module DB queries، Controller/Eloquent Model calls

### 10. Size Constraints
- Class: 300 lines max
- Method: 30 lines max
- Controller: 100 lines max
- Constructor args: 5 max

---

## القيمة الحقيقية بعد اكتمال الوحدة الثالثة

بعد اكتمال الوحدة الثالثة يصبح رفيق ليس مجرد:
- ❌ Student Management System

بل يتحول إلى:
- ✅ Student Success Platform
- ✅ Career Development Platform
- ✅ Academic Intelligence Platform

وهنا تبدأ الفكرة تصبح مختلفة فعلاً عن معظم الأنظمة الجامعية التقليدية، لأن النظام لا يكتفي بإدارة الدراسة بل يساعد الطالب على الوصول إلى الوظيفة المناسبة وبناء مساره المهني بالكامل.

---

## الحالة الحالية

**Phase:** Phase 0 — Planning  
**Implementation:** Not started  
**نسبة الإنجاز:** 0%

---

## التالي

بعد موافقة المستخدم على هذه الخطة، سيتم البدء في التنفيذ مرحلة بمرحلة وفقاً للترتيب المحدد أعلاه.
