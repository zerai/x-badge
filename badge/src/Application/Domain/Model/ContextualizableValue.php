<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model;

interface ContextualizableValue
{
    public function asBadgeValue(): string;
}
