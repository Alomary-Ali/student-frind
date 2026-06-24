<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Integrations;

use Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface;
use OpenAI\Laravel\Facades\OpenAI;

final class OpenAiAssistantService implements AiAssistantGatewayInterface
{
    private const SYSTEM_PROMPT = 'أنت مساعد ذكي لخدمات الطلاب في جامعة سعودية. أجب باللغة العربية الفصحى. كن دقيقاً ومختصراً.';

    public function ask(string $conversationId, string $studentId, string $message, array $context = []): array
    {
        $messages = [
            ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
        ];

        if (! empty($context['history'])) {
            foreach ($context['history'] as $msg) {
                $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
            }
        }

        if (! empty($context['knowledge'])) {
            $knowledgeContext = "المعلومات المتاحة:\n";
            foreach ($context['knowledge'] as $article) {
                $knowledgeContext .= "- {$article['title']}: {$article['content']}\n";
            }
            $messages[] = ['role' => 'system', 'content' => $knowledgeContext];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
                'max_tokens' => 1000,
            ]);

            $reply = $response->choices[0]->message->content ?? 'عذراً، لم أتمكن من معالجة طلبك.';

            return [
                'reply' => $reply,
                'tokens_used' => $response->usage->totalTokens ?? 0,
            ];
        } catch (\Exception $e) {
            return [
                'reply' => 'عذراً، حدث خطأ في الاتصال بالمساعد الذكي. الرجاء المحاولة لاحقاً.',
                'tokens_used' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function generateSuggestions(string $conversationId, string $messageId, array $context = []): array
    {
        return [
            ['type' => 'reply', 'title' => 'كيف يمكنني تقديم طلب جديد؟', 'action_url' => '/services'],
            ['type' => 'reply', 'title' => 'ما هي حالة طلباتي الحالية؟', 'action_url' => '/requests'],
            ['type' => 'reply', 'title' => 'أريد معرفة المزيد عن الخدمات المتاحة', 'action_url' => '/services'],
        ];
    }

    public function searchKnowledge(string $query): array
    {
        return [
            'query' => $query,
            'results' => [],
        ];
    }
}
