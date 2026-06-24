<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;
use Modules\StudentServices\Domain\Contracts\DocumentRepositoryInterface;
use Modules\StudentServices\Domain\Contracts\Gateways\NotificationGatewayInterface;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;

final readonly class GetStudentServicesDashboard
{
    public function __construct(
        private ServiceRequestRepositoryInterface $requests,
        private DocumentRepositoryInterface $documents,
        private ConversationRepositoryInterface $conversations,
        private NotificationGatewayInterface $notifications,
    ) {}

    public function execute(string $studentId): array
    {
        $allRequests = $this->requests->findByStudentId($studentId);
        $allDocuments = $this->documents->findByStudentId($studentId);

        $activeRequests = array_filter($allRequests, fn ($r) => ! in_array($r->status()->value, ['completed', 'cancelled', 'rejected'], true));
        $pendingDocuments = array_filter($allDocuments, fn ($d) => $d->status()->value === 'pending');

        $recentRequests = array_slice($allRequests, 0, 5);
        $recentMessages = $this->conversations->findActiveByStudentId($studentId);

        return [
            'active_requests_count' => count($activeRequests),
            'pending_documents_count' => count($pendingDocuments),
            'unread_notifications' => $this->notifications->getUnreadCount($studentId),
            'recent_requests' => array_map(fn ($r) => [
                'id' => $r->id()->value(),
                'ref_number' => $r->refNumber(),
                'status' => $r->status()->value,
                'created_at' => $r->createdAt()->format('c'),
            ], $recentRequests),
            'recent_conversation' => $recentMessages?->id()->value(),
            'available_services' => [
                'document_request',
                'certificate',
                'transcript',
                'statement',
                'official_letter',
            ],
        ];
    }
}
