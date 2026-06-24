<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Integrations;

use Barryvdh\DomPDF\Facade\Pdf;

final class DompdfDocumentGenerator implements DocumentGeneratorInterface
{
    public function generate(string $type, array $data): string
    {
        $viewName = match ($type) {
            'transcript' => 'documents.transcript',
            'certificate' => 'documents.certificate',
            'statement' => 'documents.statement',
            default => 'documents.default',
        };

        $pdf = Pdf::loadView("student-services::{$viewName}", $data);
        $filename = "document_{$type}_" . time() . '.pdf';
        $path = storage_path("app/documents/{$filename}");

        $pdf->save($path);

        return "documents/{$filename}";
    }

    public function verify(string $documentPath): bool
    {
        return file_exists(storage_path("app/{$documentPath}"));
    }
}
