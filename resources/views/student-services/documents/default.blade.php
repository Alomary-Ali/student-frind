<!DOCTYPE html>
<html dir="rtl">
<head><meta charset="utf-8"><title>مستند</title></head>
<body style="font-family: 'DejaVu Sans', sans-serif; padding: 40px;">
    <h1 style="text-align: center;">مستند رسمي</h1>
    <hr>
    <p>@lang('student-services.date'): {{ date('Y-m-d') }}</p>
    <hr>
    <p>{{ $content ?? '' }}</p>
</body>
</html>
