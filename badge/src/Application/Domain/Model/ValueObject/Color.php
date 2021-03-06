<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ValueObject;

use Webmozart\Assert\Assert;

final class Color
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $this->check($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(?self $other): bool
    {
        return $other !== null && $this->value === $other->value;
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
        //Assert::minLength($data, (int) self::MIN_LENGTH);
        //Assert::maxLength($data, (int) self::MAX_LENGTH);

        return $data;
    }
}
