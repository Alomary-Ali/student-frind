<?php

declare(strict_types=1);

namespace Modules\StudentServices;

use Illuminate\Support\ServiceProvider;
use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;
use Modules\StudentServices\Domain\Contracts\DocumentRepositoryInterface;
use Modules\StudentServices\Domain\Contracts\DocumentRequestRepositoryInterface;
use Modules\StudentServices\Domain\Contracts\FaqRepositoryInterface;
use Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface;
use Modules\StudentServices\Domain\Contracts\Gateways\NotificationGatewayInterface;
use Modules\StudentServices\Domain\Contracts\KnowledgeRepositoryInterface;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Infrastructure\Gateways\NotificationGateway;
use Modules\StudentServices\Infrastructure\Integrations\DocumentGeneratorInterface;
use Modules\StudentServices\Infrastructure\Integrations\DompdfDocumentGenerator;
use Modules\StudentServices\Infrastructure\Integrations\OpenAiAssistantService;
use Modules\StudentServices\Infrastructure\Persistence\EloquentConversationRepository;
use Modules\StudentServices\Infrastructure\Persistence\EloquentDocumentRepository;
use Modules\StudentServices\Infrastructure\Persistence\EloquentDocumentRequestRepository;
use Modules\StudentServices\Infrastructure\Persistence\EloquentFaqRepository;
use Modules\StudentServices\Infrastructure\Persistence\EloquentKnowledgeRepository;
use Modules\StudentServices\Infrastructure\Persistence\EloquentServiceRequestRepository;

final class StudentServicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ServiceRequestRepositoryInterface::class, EloquentServiceRequestRepository::class);
        $this->app->bind(DocumentRepositoryInterface::class, EloquentDocumentRepository::class);
        $this->app->bind(DocumentRequestRepositoryInterface::class, EloquentDocumentRequestRepository::class);
        $this->app->bind(KnowledgeRepositoryInterface::class, EloquentKnowledgeRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, EloquentFaqRepository::class);
        $this->app->bind(ConversationRepositoryInterface::class, EloquentConversationRepository::class);

        $this->app->bind(DocumentGeneratorInterface::class, DompdfDocumentGenerator::class);
        $this->app->bind(AiAssistantGatewayInterface::class, OpenAiAssistantService::class);

        $this->app->bind(NotificationGatewayInterface::class, NotificationGateway::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Presentation/Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/Infrastructure/Database');
    }
}
