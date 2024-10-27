<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model;

interface BadgeContext
{
    /**
     * @return string[]
     *
     * @psalm-return array{subject: string, subject-value: string, color: string}
     */
    public function renderingProperties(): array;
}
