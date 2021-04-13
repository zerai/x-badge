<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue\Common;

use Badge\Application\Domain\Model\ContextualizableValue;
use Webmozart\Assert\Assert;

abstract class PostFixCount implements ContextualizableValue
{
    use  CountNormalizerTrait;

    private int $value;

    private string $suffix = '';

    public function __construct(int $value)
    {
        $this->value = $this->validate($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function asBadgeValue(): string
    {
        return $this->normalize($this->value) . $this->suffix();
    }

    abstract protected function suffix(): string;

    private function validate(int $inputData): int
    {
        Assert::greaterThanEq($inputData, 0);

        return $inputData;
    }
}
