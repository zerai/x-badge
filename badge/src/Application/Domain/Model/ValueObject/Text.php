<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ValueObject;

use Webmozart\Assert\Assert;

final class Text
{
    private const MIN_LENGTH = 1;

    private const MAX_LENGTH = 10;

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $this->check($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function check(string $inputData): string
    {
        return $this->validate(
            $this->normalize($inputData)
        );
    }

    private function normalize(string $inputData): string
    {
        return \trim($inputData);
    }

    private function validate(string $inputData): string
    {
        $data = $this->normalize($inputData);

        Assert::stringNotEmpty($data);
        Assert::minLength($data, self::MIN_LENGTH);
        Assert::maxLength($data, self::MAX_LENGTH);

        return $data;
    }
}
