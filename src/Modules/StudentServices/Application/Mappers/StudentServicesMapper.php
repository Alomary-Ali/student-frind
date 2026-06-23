<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\Mappers;

use Modules\StudentServices\Application\DTOs\AssistantConversationDto;
use Modules\StudentServices\Application\DTOs\AssistantMessageDto;
use Modules\StudentServices\Application\DTOs\AssistantSuggestionDto;
use Modules\StudentServices\Application\DTOs\DocumentRequestDto;
use Modules\StudentServices\Application\DTOs\FaqDto;
use Modules\StudentServices\Application\DTOs\KnowledgeArticleDto;
use Modules\StudentServices\Application\DTOs\ServiceCategoryDto;
use Modules\StudentServices\Application\DTOs\ServiceRequestDto;
use Modules\StudentServices\Application\DTOs\ServiceWorkflowDto;
use Modules\StudentServices\Application\DTOs\StudentDocumentDto;
use Modules\StudentServices\Application\DTOs\StudentServicesDashboardDto;
use Modules\StudentServices\Application\DTOs\WorkflowStepDto;
use Modules\StudentServices\Domain\Entities\AssistantConversation;
use Modules\StudentServices\Domain\Entities\AssistantMessage;
use Modules\StudentServices\Domain\Entities\AssistantSuggestion;
use Modules\StudentServices\Domain\Entities\DocumentRequest;
use Modules\StudentServices\Domain\Entities\FAQ;
use Modules\StudentServices\Domain\Entities\KnowledgeArticle;
use Modules\StudentServices\Domain\Entities\ServiceCategory;
use Modules\StudentServices\Domain\Entities\ServiceRequest;
use Modules\StudentServices\Domain\Entities\ServiceWorkflow;
use Modules\StudentServices\Domain\Entities\StudentDocument;
use Modules\StudentServices\Domain\Entities\WorkflowStep;

final class StudentServicesMapper
{
    public function toServiceCategoryDto(ServiceCategory $entity): ServiceCategoryDto
    {
        return new ServiceCategoryDto(
            id: $entity->id(),
            name: $entity->name(),
            type: $entity->type()->value,
            description: $entity->description(),
            isActive: $entity->isActive(),
            sortOrder: $entity->sortOrder(),
        );
    }

    public function toServiceRequestDto(ServiceRequest $entity): ServiceRequestDto
    {
        return new ServiceRequestDto(
            id: $entity->id()->value(),
            refNumber: $entity->refNumber(),
            categoryId: $entity->categoryId(),
            studentId: $entity->studentId(),
            status: $entity->status()->value,
            priority: $entity->priority()->value,
            notes: $entity->notes(),
            adminNotes: $entity->adminNotes(),
            createdAt: $entity->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $entity->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    public function toStudentDocumentDto(StudentDocument $entity): StudentDocumentDto
    {
        return new StudentDocumentDto(
            id: $entity->id()->value(),
            studentId: $entity->studentId(),
            type: $entity->type()->value,
            title: $entity->title(),
            filePath: $entity->filePath(),
            status: $entity->status()->value,
            verificationCode: $entity->verificationCode(),
            metadata: $entity->metadata(),
            createdAt: $entity->createdAt()->format('Y-m-d H:i:s'),
        );
    }

    public function toDocumentRequestDto(DocumentRequest $entity): DocumentRequestDto
    {
        return new DocumentRequestDto(
            id: $entity->id()->value(),
            studentId: $entity->studentId(),
            documentType: $entity->documentType()->value,
            status: $entity->status()->value,
            notes: $entity->notes(),
            createdAt: $entity->createdAt()->format('Y-m-d H:i:s'),
        );
    }

    public function toKnowledgeArticleDto(KnowledgeArticle $entity): KnowledgeArticleDto
    {
        return new KnowledgeArticleDto(
            id: $entity->id()->value(),
            categoryId: $entity->categoryId(),
            title: $entity->title(),
            slug: $entity->slug(),
            content: $entity->content(),
            tags: $entity->tags(),
            status: $entity->status()->value,
            viewCount: $entity->viewCount(),
            createdAt: $entity->createdAt()->format('Y-m-d H:i:s'),
        );
    }

    public function toFaqDto(FAQ $entity): FaqDto
    {
        return new FaqDto(
            id: $entity->id(),
            categoryId: $entity->categoryId(),
            question: $entity->question(),
            answer: $entity->answer(),
            sortOrder: $entity->sortOrder(),
            isActive: $entity->isActive(),
        );
    }

    public function toConversationDto(AssistantConversation $entity): AssistantConversationDto
    {
        return new AssistantConversationDto(
            id: $entity->id()->value(),
            studentId: $entity->studentId(),
            title: $entity->title(),
            status: $entity->status()->value,
            lastActivityAt: $entity->lastActivityAt()->format('Y-m-d H:i:s'),
            createdAt: $entity->createdAt()->format('Y-m-d H:i:s'),
        );
    }

    public function toMessageDto(AssistantMessage $entity): AssistantMessageDto
    {
        return new AssistantMessageDto(
            id: $entity->id()->value(),
            conversationId: $entity->conversationId(),
            role: $entity->role()->value,
            content: $entity->content(),
            createdAt: $entity->createdAt()->format('Y-m-d H:i:s'),
        );
    }

    public function toSuggestionDto(AssistantSuggestion $entity): AssistantSuggestionDto
    {
        return new AssistantSuggestionDto(
            id: $entity->id(),
            conversationId: $entity->conversationId(),
            messageId: $entity->messageId(),
            suggestionType: $entity->suggestionType(),
            title: $entity->title(),
            actionUrl: $entity->actionUrl(),
        );
    }

    public function toWorkflowDto(ServiceWorkflow $entity): ServiceWorkflowDto
    {
        return new ServiceWorkflowDto(
            id: $entity->id(),
            serviceCategoryId: $entity->serviceCategoryId(),
            name: $entity->name(),
            status: $entity->status()->value,
        );
    }

    public function toWorkflowStepDto(WorkflowStep $entity): WorkflowStepDto
    {
        return new WorkflowStepDto(
            id: $entity->id()->value(),
            workflowId: $entity->workflowId(),
            name: $entity->name(),
            type: $entity->type()->value,
            order: $entity->order(),
            assigneeRole: $entity->assigneeRole(),
            status: $entity->status()->value,
        );
    }

    public function toDashboardDto(array $data): StudentServicesDashboardDto
    {
        return new StudentServicesDashboardDto(
            activeRequests: (int) ($data['active_requests'] ?? 0),
            pendingDocuments: (int) ($data['pending_documents'] ?? 0),
            unreadNotifications: (int) ($data['unread_notifications'] ?? 0),
            recentRequests: $data['recent_requests'] ?? [],
            recentMessages: $data['recent_messages'] ?? [],
            services: $data['services'] ?? [],
        );
    }
}
