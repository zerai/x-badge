<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue\Common;

use Badge\Application\Domain\Model\ContextualizableValue;
use Webmozart\Assert\Assert;

abstract class BaseCount implements ContextualizableValue
{
    use  CountNormalizerTrait;

    private int $value;

    private ?string $suffix = null;

    public function __construct(int $value, string $suffix = '')
    {
        $this->value = $this->validate($value);

        $this->suffix = $suffix;
    }

    public function asBadgeValue(): string
    {
        /** @psalm-suppress PossiblyNullArgument */
        return \trim(\sprintf('%s %s', $this->normalize($this->value), $this->suffix));
    }

    private function validate(int $inputData): int
    {
        Assert::greaterThanEq($inputData, 0);

        return $inputData;
    }
}
