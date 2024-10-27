<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\ContextValue;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextualizableValue;
use InvalidArgumentException;

class License implements ContextualizableValue, BadgeContext
{
    private const COLOR = '428F7E';

    private const SUBJECT = 'license';

    private const TEXT_NO_LICENSE = 'no';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $this->check($value);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function withNoLicense(): self
    {
        return new self(self::TEXT_NO_LICENSE);
    }

    /**
     * @return string[]
     *
     * @psalm-return array{subject: string, subject-value: string, color: string}
     */
    public function renderingProperties(): array
    {
        return [
            'subject' => self::SUBJECT,
            'subject-value' => $this->asBadgeValue(),
            'color' => self::COLOR,
        ];
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
        return trim($inputData);
    }

    private function validate(string $inputData): string
    {
        $data = $this->normalize($inputData);

        if ($data === '') {
            throw new InvalidArgumentException('License with empty value is not allowed.');
        }

        return $data;
    }
}
