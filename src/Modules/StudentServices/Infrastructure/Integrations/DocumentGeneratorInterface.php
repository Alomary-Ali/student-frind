<?php
declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Integrations;

interface DocumentGeneratorInterface
{
    public function generate(string $type, array $data): string;
    public function verify(string $documentPath): bool;
}
