<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model;

interface RenderableValue
{
    public function renderingProperties(): array;
}
