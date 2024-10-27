<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model;

interface ContextualizableValue
{
    public function asBadgeValue(): string;
}
