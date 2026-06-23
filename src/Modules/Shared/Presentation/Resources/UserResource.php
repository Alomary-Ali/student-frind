<?php

declare(strict_types=1);

namespace Modules\Shared\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shared\Application\DTOs\UserDto;

/**
 * @property-read UserDto $resource
 */
final class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'email' => $this->resource->email,
            'first_name' => $this->resource->firstName,
            'last_name' => $this->resource->lastName,
            'full_name' => $this->resource->fullName,
            'role' => $this->resource->role,
            'status' => $this->resource->status,
            'email_verified_at' => $this->resource->emailVerifiedAt,
            'created_at' => $this->resource->createdAt,
        ];
    }
}
