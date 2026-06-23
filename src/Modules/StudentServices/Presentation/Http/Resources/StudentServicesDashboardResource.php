<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class StudentServicesDashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = $this->resource;

        return [
            'active_requests_count' => $data['active_requests_count'] ?? 0,
            'pending_documents_count' => $data['pending_documents_count'] ?? 0,
            'unread_notifications' => $data['unread_notifications'] ?? 0,
            'recent_requests' => $data['recent_requests'] ?? [],
            'recent_conversation' => $data['recent_conversation'] ?? null,
            'available_services' => $data['available_services'] ?? [],
        ];
    }
}
