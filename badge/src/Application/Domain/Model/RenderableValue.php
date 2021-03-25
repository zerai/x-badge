<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model;

interface RenderableValue
{
    /**
     * @return string[]
     *
     * @psalm-return array{subject: string, subject-value: string, color: string}
     */
    public function renderingProperties(): array;
}
