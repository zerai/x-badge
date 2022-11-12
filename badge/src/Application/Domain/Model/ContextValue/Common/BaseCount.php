<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue\Common;

use Badge\Application\Domain\Model\ContextualizableValue;
use InvalidArgumentException;

abstract class BaseCount implements ContextualizableValue
{
    use CountNormalizerTrait;

    private int $value;

    private ?string $suffix = null;

    protected function __construct(int $value, string $suffix = '')
    {
        $this->value = $this->validate($value);

        $this->suffix = $suffix;
    }

    /**
     * @psalm-suppress all
     */
    /* @phpstan-ignore-next-line */
    abstract public static function withCount(int $value);

    public function asBadgeValue(): string
    {
        /** @psalm-suppress PossiblyNullArgument */
        return trim(sprintf('%s %s', $this->normalize($this->value), $this->suffix));
    }

    private function validate(int $inputData): int
    {
        if ($inputData < 0) {
            throw new InvalidArgumentException(
                sprintf('Count value should be greater than or equal to 0. - %s recivied.', $inputData)
            );
        }

        return $inputData;
    }
}
