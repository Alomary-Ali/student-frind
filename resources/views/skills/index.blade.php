@extends('layouts.dashboard')

@section('title', 'المهارات والشهادات')

@section('content')
<div class="space-y-8">
    {{-- Header Banner --}}
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="absolute inset-0 orb orb-primary opacity-35 -bottom-20 -right-20"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <x-rf-badge variant="accent" class="mb-3">مركز المهارات والجدارات</x-rf-badge>
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">المهارات، الشهادات، والمسارات التعلمية</h1>
                <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">أضف مهاراتك، وثق شهاداتك الاحترافية، وتتبع الإنجازات والمسارات التي تؤهلك لسوق العمل.</p>
            </div>
            <div class="flex gap-3 shrink-0">
                <button onclick="openModal('add-skill-modal')" class="btn btn-primary btn-sm">
                    + مهارة جديدة
                </button>
                <button onclick="openModal('add-cert-modal')" class="btn btn-primary btn-sm">
                    + شهادة جديدة
                </button>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Right Side: Achievements & Learning Paths --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Achievements/Badges Card --}}
            <div class="card card-elevated p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-gradient-primary">الأوسمة والإنجازات المهنية</h2>
                    <div class="h-1 w-12 bg-primary rounded-full mt-1.5"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @forelse($achievements as $ach)
                        <div class="flex flex-col items-center text-center space-y-2 p-2 rounded-xl border" style="border-color: hsl(var(--color-border-light)); background: hsl(var(--color-background) / 0.5);">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg text-accent" style="background: linear-gradient(135deg, hsl(var(--color-accent-light)), hsl(var(--color-accent) / 0.2));">
                                🏆
                            </div>
                            <span class="text-[10px] font-black leading-tight text-text-primary">{{ $ach->title }}</span>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-6 text-sm text-text-muted">
                            لم تكتسب أي أوسمة مهنية حتى الآن. أضف مهاراتك وشهاداتك للحصول عليها!
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Learning Paths Card --}}
            <div class="card card-elevated p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-gradient-primary">مسار التعلم الموصى به</h2>
                    <div class="h-1 w-12 bg-primary rounded-full mt-1.5"></div>
                </div>

                <div class="space-y-4">
                    @forelse($learningPaths as $path)
                        <div class="p-4 rounded-2xl border space-y-3" style="border-color: hsl(var(--color-border-light)); background: hsl(var(--color-background) / 0.5);">
                            <div>
                                <x-rf-badge variant="primary" size="sm">{{ $path->targetRole }}</x-rf-badge>
                                <h3 class="text-sm font-bold mt-1.5 text-text-primary">{{ $path->title }}</h3>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between items-center text-[10px] text-text-secondary">
                                    <span>التقدم الإجمالي</span>
                                    <span>{{ $path->progress }}%</span>
                                </div>
                                <div class="progress-track">
                                    <div class="progress-fill progress-fill-accent" style="width: {{ $path->progress }}%"></div>
                                </div>
                            </div>
                            <div class="pt-2 space-y-1.5">
                                @foreach($path->steps as $step)
                                    <div class="flex items-center gap-2 text-xs text-text-secondary">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background: {{ ($step['completed'] ?? false) ? 'hsl(var(--color-accent))' : 'hsl(var(--color-text-muted))' }}"></span>
                                        <span class="{{ ($step['completed'] ?? false) ? 'line-through' : '' }}">{{ $step['title'] ?? '' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-sm text-text-muted">
                            جاري توليد مسار تعلم مخصص بناءً على اهتماماتك...
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Left Side: Skills List & Certifications --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Current Skills --}}
            <div class="card card-elevated p-6 space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gradient-primary">المهارات الحالية والجدارات</h2>
                        <div class="h-1 w-12 bg-primary rounded-full mt-1.5"></div>
                    </div>
                    <button onclick="openModal('add-skill-modal')" class="btn btn-sm btn-ghost">
                        + مهارة جديدة
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($profile->skills as $skill)
                        <div class="p-4 rounded-2xl border space-y-3" style="border-color: hsl(var(--color-border-light)); background: hsl(var(--color-background) / 0.3);">
                            <div class="flex justify-between items-center">
                                <h3 class="text-sm font-bold text-text-primary">{{ $skill->name }}</h3>
                                <x-rf-badge variant="primary" size="sm">{{ $skill->categoryLabel }}</x-rf-badge>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between items-center text-[10px] text-text-secondary">
                                    <span>المستوى: {{ $skill->levelLabel }}</span>
                                    <span>الخبرة: {{ $skill->yearsOfExperience }} سنة</span>
                                </div>
                                <div class="progress-track" style="height: 4px;">
                                    <div class="progress-fill progress-fill-primary" style="width: {{ $skill->levelWeight }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12 border border-dashed rounded-3xl space-y-4" style="border-color: hsl(var(--color-border));">
                            <svg class="mx-auto w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:hsl(var(--color-text-muted));"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            <p style="color:hsl(var(--color-text-muted));">لم تقم بإضافة أي مهارات حتى الآن.</p>
                            <button onclick="openModal('add-skill-modal')" class="btn btn-primary btn-sm mt-4">
                                أضف مهاراتك الأولى
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Certifications --}}
            <div class="card card-elevated p-6 space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gradient-primary">الشهادات والاعتمادات الموثقة</h2>
                        <div class="h-1 w-12 bg-primary rounded-full mt-1.5"></div>
                    </div>
                    <button onclick="openModal('add-cert-modal')" class="btn btn-sm btn-ghost">
                        + شهادة جديدة
                    </button>
                </div>

                <div class="space-y-4">
                    @forelse($profile->certifications as $cert)
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-4 rounded-2xl border gap-4" style="border-color: hsl(var(--color-border-light)); background: hsl(var(--color-background) / 0.3);">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-text-primary">{{ $cert->name }}</h3>
                                    @if($cert->isExpired)
                                        <x-rf-badge variant="error" size="sm">منتهية الصلاحية</x-rf-badge>
                                    @else
                                        <x-rf-badge variant="accent" size="sm">نشطة</x-rf-badge>
                                    @endif
                                </div>
                                <p class="text-xs text-text-secondary">{{ $cert->issuer }} &bull; تاريخ الإصدار: {{ $cert->issueDate }}</p>
                                @if($cert->verificationCode)
                                    <p class="text-[10px] text-text-muted">رمز التحقق: {{ $cert->verificationCode }}</p>
                                @endif
                            </div>

                            @if($cert->credentialUrl)
                                <a href="{{ $cert->credentialUrl }}" target="_blank" class="btn btn-sm btn-secondary shrink-0">
                                    معاينة الاعتماد
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 border border-dashed rounded-3xl space-y-4" style="border-color: hsl(var(--color-border));">
                            <svg class="mx-auto w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:hsl(var(--color-text-muted));"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <p style="color:hsl(var(--color-text-muted));">لم تقم بإضافة شهادات مهنية موثقة.</p>
                            <button onclick="openModal('add-cert-modal')" class="btn btn-primary btn-sm mt-4">
                                وثق شهادتك المهنية الأولى
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Skill Modal --}}
<div id="add-skill-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 p-4">
    <div class="card p-6 max-w-md w-full space-y-6 animate-scale-in">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-text-primary">إضافة مهارة جديدة</h3>
            <button onclick="closeModal('add-skill-modal')" class="text-xl font-bold text-text-muted">&times;</button>
        </div>
        <form action="{{ route('skills.skills.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">اسم المهارة</label>
                <input type="text" name="name" required placeholder="مثال: Laravel, JavaScript, Figma" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
            </div>
            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">تصنيف المهارة</label>
                <select name="category" required class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                    <option value="programming">البرمجة والتطوير</option>
                    <option value="design">التصميم وواجهات المستخدم</option>
                    <option value="ai">الذكاء الاصطناعي وهندسة البيانات</option>
                    <option value="data_analysis">تحليل البيانات والإحصاء</option>
                    <option value="leadership">القيادة والإدارة</option>
                    <option value="communication">التواصل والعرض</option>
                    <option value="teamwork">العمل الجماعي</option>
                    <option value="problem_solving">حل المشكلات والتفكير الإبداعي</option>
                    <option value="time_management">إدارة الوقت والتخطيط</option>
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">المستوى الحالي</label>
                    <select name="level" required class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                        <option value="beginner">مبتدئ</option>
                        <option value="intermediate">متوسط</option>
                        <option value="advanced">متقدم</option>
                        <option value="expert">خبير</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">سنوات الخبرة</label>
                    <input type="number" name="years_of_experience" min="0" value="0" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('add-skill-modal')" class="btn btn-sm btn-ghost">إلغاء</button>
                <button type="submit" class="btn btn-sm btn-primary">إضافة المهارة</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Cert Modal --}}
<div id="add-cert-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 p-4">
    <div class="card p-6 max-w-lg w-full space-y-6 animate-scale-in">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-text-primary">إضافة شهادة مهنية</h3>
            <button onclick="closeModal('add-cert-modal')" class="text-xl font-bold text-text-muted">&times;</button>
        </div>
        <form action="{{ route('skills.certifications.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">اسم الشهادة</label>
                    <input type="text" name="name" required placeholder="مثال: AWS Certified Solutions Architect" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">الجهة المانحة</label>
                    <input type="text" name="issuer" required placeholder="مثال: Amazon Web Services" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">تاريخ الإصدار</label>
                    <input type="date" name="issue_date" required class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">تاريخ الانتهاء (اختياري)</label>
                    <input type="date" name="expiry_date" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">رابط الشهادة / الاعتماد</label>
                    <input type="url" name="credential_url" placeholder="https://" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">الرمز التعريفي للشهادة</label>
                    <input type="text" name="verification_code" placeholder="مثال: Verification Code or ID" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('add-cert-modal')" class="btn btn-sm btn-ghost">إلغاء</button>
                <button type="submit" class="btn btn-sm btn-primary">إضافة الشهادة</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endpush
@endsection
