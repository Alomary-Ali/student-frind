<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Skills\Application\DTOs\CertificationDto;

/**
 * @property-read CertificationDto $resource
 */
final class CertificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'skill_profile_id' => $this->resource->skillProfileId,
            'name' => $this->resource->name,
            'issuer' => $this->resource->issuer,
            'issue_date' => $this->resource->issueDate,
            'expiry_date' => $this->resource->expiryDate,
            'credential_url' => $this->resource->credentialUrl,
            'verification_code' => $this->resource->verificationCode,
            'is_expired' => $this->resource->isExpired,
        ];
    }
}
