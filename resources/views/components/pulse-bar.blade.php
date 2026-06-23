@props([
    'userName' => 'طالب رفيق',
    'gpa' => 0,
    'progress' => 0,
    'readiness' => 0,
    'skills' => null,
    'courses' => null,
])

<div class="pulsebar" id="pulsebar">
    <div class="pulsebar-inner">

        {{-- Student name (always visible) --}}
        <div class="pulsebar-item">
            <div class="pulsebar-dot"></div>
            <span class="pulsebar-name" id="pulsebar-name">{{ $userName }}</span>
        </div>

        <div class="pulsebar-divider"></div>

        {{-- GPA (always visible) --}}
        <div class="pulsebar-item">
            <span class="pulsebar-label">المعدل</span>
            <span class="pulsebar-value" id="pulsebar-gpa">{{ number_format($gpa, 2) }}</span>
        </div>

        <div class="pulsebar-divider"></div>

        {{-- Progress (always visible) --}}
        <div class="pulsebar-item">
            <span class="pulsebar-label">التقدم</span>
            <span class="pulsebar-value" id="pulsebar-progress">{{ $progress }}%</span>
        </div>

        {{-- Tablet+ (≥ 768px): Readiness --}}
        <div class="pulsebar-tablet">
            <div class="pulsebar-divider"></div>
            <div class="pulsebar-item">
                <span class="pulsebar-label">الجاهزية</span>
                <span class="pulsebar-value pulsebar-value-accent" id="pulsebar-readiness">{{ $readiness }}%</span>
            </div>
        </div>

        {{-- Desktop (≥ 1024px): Skills + Courses --}}
        @if(!is_null($skills) || !is_null($courses))
            <div class="pulsebar-desktop">
                <div class="pulsebar-divider"></div>
                @if(!is_null($skills))
                    <div class="pulsebar-item">
                        <span class="pulsebar-label">المهارات</span>
                        <span class="pulsebar-value" id="pulsebar-skills">{{ $skills }}</span>
                    </div>
                @endif
                @if(!is_null($courses))
                    <div class="pulsebar-item mr-4">
                        <span class="pulsebar-label">المواد</span>
                        <span class="pulsebar-value" id="pulsebar-courses">{{ $courses }}</span>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
(function() {
    const elName = document.getElementById('pulsebar-name');
    const elGpa  = document.getElementById('pulsebar-gpa');
    const elProg = document.getElementById('pulsebar-progress');
    const elRead = document.getElementById('pulsebar-readiness');
    const elSkil = document.getElementById('pulsebar-skills');
    const elCour = document.getElementById('pulsebar-courses');

    if (!elGpa) return;

    let interval = setInterval(fetchPulseBar, 30000);

    function fetchPulseBar() {
        fetch('/api/pulsebar/data')
            .then(r => r.json())
            .then(d => {
                if (elName) elName.textContent = d.userName;
                if (elGpa)  elGpa.textContent = Number(d.gpa).toFixed(2);
                if (elProg) elProg.textContent = d.progress + '%';
                if (elRead) elRead.textContent = d.readiness + '%';
                if (elSkil) elSkil.textContent = d.skills;
                if (elCour) elCour.textContent = d.courses;
            })
            .catch(() => {});
    }
})();
</script>
@endpush
