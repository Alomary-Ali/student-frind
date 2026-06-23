<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum WorkflowStepType: string
{
    case FORM = 'form';
    case APPROVAL = 'approval';
    case DOCUMENT = 'document';
    case NOTIFICATION = 'notification';
    case CONDITION = 'condition';

    public function label(): string
    {
        return match ($this) {
            self::FORM => 'نموذج',
            self::APPROVAL => 'موافقة',
            self::DOCUMENT => 'وثيقة',
            self::NOTIFICATION => 'إشعار',
            self::CONDITION => 'شرط',
        };
    }
}
