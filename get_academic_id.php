<?php

declare(strict_types=1);
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'student@rafiq.test')->first();
echo 'Academic ID: ' . $user->academic_id . "\n";
echo 'Email: ' . $user->email . "\n";
echo "Password: Student@1234\n";
