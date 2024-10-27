<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\ContextValue;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextualizableValue;
use InvalidArgumentException;

final class UnstableVersion implements ContextualizableValue, BadgeContext
{
    private const COLOR_UNSTABLE = 'e68718';

    private const SUBJECT_UNSTABLE = 'unstable';

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
            'subject' => self::SUBJECT_UNSTABLE,
            'subject-value' => $this->asBadgeValue(),
            'color' => self::COLOR_UNSTABLE,
        ];
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
            throw new InvalidArgumentException('Unstable Version with empty value is not allowed.');
        }

        if (\strlen($data) < self::MIN_LENGTH) {
            throw new InvalidArgumentException('Unstable Version value is too short.');
        }

        return $data;
    }
}
