<?php

declare(strict_types=1);
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Bootstrap console first to setup database connection
$consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$consoleKernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Let's resolve the student user
$user = App\Models\User::where('email', 'student@rafiq.test')->first();
if (! $user) {
    echo "Student user not found!\n";
    exit(1);
}

echo "Simulating request to /career for User: {$user->email} (ID: {$user->id}, Role: {$user->role})\n";

// Log in the user
Auth::login($user);

// Bootstrap HTTP kernel
$httpKernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create request
$request = Request::create('/career', 'GET');
// Associate session to the request
$request->setLaravelSession($app['session']->driver());

$response = $httpKernel->handle($request);

echo 'Response Status: ' . $response->getStatusCode() . "\n";
echo 'Redirect Target: ' . $response->headers->get('Location') . "\n";
if ($response->getStatusCode() === 302 && session()->has('error')) {
    echo 'Session Error: ' . session()->get('error') . "\n";
}
if ($response->getStatusCode() === 302 && session()->has('errors')) {
    echo 'Session Errors: ' . json_encode(session()->get('errors')->all()) . "\n";
}

echo "\n-------------------------------------------------\n";

echo "Simulating request to /skills for User: {$user->email}\n";
$request2 = Request::create('/skills', 'GET');
$request2->setLaravelSession($app['session']->driver());
$response2 = $httpKernel->handle($request2);

echo 'Response Status: ' . $response2->getStatusCode() . "\n";
echo 'Redirect Target: ' . $response2->headers->get('Location') . "\n";
if ($response2->getStatusCode() === 302 && session()->has('error')) {
    echo 'Session Error: ' . session()->get('error') . "\n";
}

$httpKernel->terminate($request, $response);
