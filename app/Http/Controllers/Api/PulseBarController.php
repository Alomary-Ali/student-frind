<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PulseBarDataResolver;
use Illuminate\Http\JsonResponse;

final class PulseBarController extends Controller
{
    public function __invoke(PulseBarDataResolver $resolver): JsonResponse
    {
        $userId = (string) auth()->id();

        $data = $resolver->resolve($userId);

        $userName = auth()->check()
            ? (auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? '')
            : 'طالب رفيق';

        return response()->json([
            'userName' => $userName,
            'gpa' => $data['gpa'],
            'progress' => $data['progress'],
            'readiness' => $data['readiness'],
            'skills' => $data['skills'],
            'courses' => $data['courses'],
        ]);
    }
}
