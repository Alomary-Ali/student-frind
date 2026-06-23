<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

final readonly class Permission
{
    private function __construct(
        public string $value,
    ) {
        $this->validate();
    }

    public static function of(string $value): self
    {
        return new self($value);
    }

    public static function studentsView(): self
    {
        return new self('students.view');
    }

    public static function studentsCreate(): self
    {
        return new self('students.create');
    }

    public static function studentsUpdate(): self
    {
        return new self('students.update');
    }

    public static function studentsDelete(): self
    {
        return new self('students.delete');
    }

    public static function enrollmentsView(): self
    {
        return new self('enrollments.view');
    }

    public static function enrollmentsCreate(): self
    {
        return new self('enrollments.create');
    }

    public static function enrollmentsDelete(): self
    {
        return new self('enrollments.delete');
    }

    public static function gradesView(): self
    {
        return new self('grades.view');
    }

    public static function gradesCreate(): self
    {
        return new self('grades.create');
    }

    public static function gradesUpdate(): self
    {
        return new self('grades.update');
    }

    public static function reportsView(): self
    {
        return new self('reports.view');
    }

    public static function settingsManage(): self
    {
        return new self('settings.manage');
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(): void
    {
        if (empty($this->value)) {
            throw new \InvalidArgumentException('Permission cannot be empty');
        }

        if (! preg_match('/^[a-z]+\.[a-z]+$/', $this->value)) {
            throw new \InvalidArgumentException('Permission must be in format: resource.action');
        }
    }
}
