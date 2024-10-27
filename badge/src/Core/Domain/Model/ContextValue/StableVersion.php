<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\ContextValue;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextualizableValue;
use InvalidArgumentException;

final class StableVersion implements ContextualizableValue, BadgeContext
{
    private const COLOR_STABLE = '28a3df';

    private const SUBJECT_STABLE = 'stable';

    private const TEXT_NO_STABLE_RELEASE = 'No Release';

    /**
     * Valore minimo di riferimento 0.0.0
     */
    private const MIN_LENGTH = 5;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $this->check($value);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function withNoRelease(): self
    {
        return new self(self::TEXT_NO_STABLE_RELEASE);
    }

    public function asBadgeValue(): string
    {
        return $this->value;
    }

    /**
     * @return string[]
     *
     * @psalm-return array{subject: string, subject-value: string, color: string}
     */
    public function renderingProperties(): array
    {
        return [
            'subject' => self::SUBJECT_STABLE,
            'subject-value' => $this->asBadgeValue(),
            'color' => self::COLOR_STABLE,
        ];
    }

    private function check(string $inputData): string
    {
        if ($inputData === self::TEXT_NO_STABLE_RELEASE) {
            return $inputData;
        }

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
            throw new InvalidArgumentException('Stable Version with empty value is not allowed.');
        }

        if (\strlen($data) < self::MIN_LENGTH) {
            throw new InvalidArgumentException('Stable Version value is too short.');
        }

        return $data;
    }
}
