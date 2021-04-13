<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue\Common;

use Badge\Application\Domain\Model\ContextualizableValue;
use Webmozart\Assert\Assert;

abstract class BaseCount implements ContextualizableValue
{
    use  CountNormalizerTrait;

    private int $value;

    public function __construct(int $value)
    {
        $this->value = $this->validate($value);
    }

    public function asBadgeValue(): string
    {
        return $this->normalize($this->value);
    }

    private function validate(int $inputData): int
    {
        Assert::greaterThanEq($inputData, 0);

        return $inputData;
    }
}
