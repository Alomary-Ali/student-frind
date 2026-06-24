<!DOCTYPE html>
<html dir="rtl">
<head><meta charset="utf-8"><title>كشف درجات</title></head>
<body style="font-family: 'DejaVu Sans', sans-serif; padding: 40px;">
    <h1 style="text-align: center;">كشف درجات</h1>
    <hr>
    <p>@lang('student-services.name'): {{ $name ?? '' }}</p>
    <p>@lang('student-services.student_id'): {{ $studentId ?? '' }}</p>
    <p>@lang('student-services.date'): {{ date('Y-m-d') }}</p>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background: #eee;">
                <th style="border: 1px solid #ccc; padding: 8px;">@lang('student-services.course')</th>
                <th style="border: 1px solid #ccc; padding: 8px;">@lang('student-services.grade')</th>
                <th style="border: 1px solid #ccc; padding: 8px;">@lang('student-services.hours')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses ?? [] as $course)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 8px;">{{ $course['name'] ?? '' }}</td>
                    <td style="border: 1px solid #ccc; padding: 8px;">{{ $course['grade'] ?? '' }}</td>
                    <td style="border: 1px solid #ccc; padding: 8px;">{{ $course['hours'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
