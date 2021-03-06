<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ValueObject;

final class Subject
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(?self $other): bool
    {
        return $other !== null && $this->value === $other->value;
    }
}
