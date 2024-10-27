<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\ContextValue\Common;

use Badge\Core\Domain\Model\ContextualizableValue;
use InvalidArgumentException;

abstract class CommittedFile implements ContextualizableValue
{
    private const ALLOWED_VALUES = [
        'committed',
        'uncommitted',
        'checking',
    ];

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $this->check($value);
    }

    /**
     * @phpstan-ignore-next-line
     * @psalm-suppress MissingReturnType
     */
    abstract public static function createAsCommitted();

    /**
     * @phpstan-ignore-next-line
     * @psalm-suppress MissingReturnType
     */
    abstract public static function createAsUncommitted();

    /**
     * @phpstan-ignore-next-line
     * @psalm-suppress MissingReturnType
     */
    abstract public static function createAsError();

    public function value(): string
    {
        return $this->value;
    }

    public function asBadgeValue(): string
    {
        return $this->value;
    }

    private function check(string $inputData): string
    {
        return $this->validate(
            $this->normalize($inputData)
        );
    }

    private function normalize(string $inputData): string
    {
        return strtolower(trim($inputData));
    }

    private function validate(string $inputData): string
    {
        $data = $this->normalize($inputData);

        if ($data === '') {
            throw new InvalidArgumentException('Empty value is not allowed.');
        }

        if (! \in_array($data, self::ALLOWED_VALUES)) {
            throw new InvalidArgumentException(sprintf('%s is not a valid value.', $data));
        }

        return $data;
    }
}
