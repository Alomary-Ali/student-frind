<!DOCTYPE html>
<html dir="rtl">
<head><meta charset="utf-8"><title>إفادة</title></head>
<body style="font-family: 'DejaVu Sans', sans-serif; padding: 40px;">
    <h1 style="text-align: center;">إفادة</h1>
    <hr>
    <p>@lang('student-services.name'): {{ $name ?? '' }}</p>
    <p>@lang('student-services.student_id'): {{ $studentId ?? '' }}</p>
    <p>@lang('student-services.date'): {{ date('Y-m-d') }}</p>
    <hr>
    <p>{{ $content ?? '' }}</p>
</body>
</html>
