<?php

declare(strict_types=1);

namespace Badge\Tests\Discovery\VO;

/**
 * null
 * @codeCoverageIgnore
 */
final class SubjectValue
{
    private Text $text;

    private Color $color;

    public function __construct(Text $text, Color $color)
    {
        $this->text = $text;
        $this->color = $color;
    }

    public function text(): Text
    {
        return $this->text;
    }

    public function withText(Text $text): self
    {
        return new self(
            $text,
            $this->color
        );
    }

    public function color(): Color
    {
        return $this->color;
    }

    public function withColor(Color $color): self
    {
        return new self(
            $this->text,
            $color
        );
    }

    public static function fromArray(array $data): self
    {
        if (! isset($data['text']) || ! \is_string($data['text'])) {
            throw new \InvalidArgumentException('Error on "text", string expected');
        }

        if (! isset($data['color']) || ! \is_string($data['color'])) {
            throw new \InvalidArgumentException('Error on "color", string expected');
        }

        return new self(
            new Text($data['text']),
            new Color($data['color']),
        );
    }

    public function toArray(): array
    {
        return [
            'text' => $this->text->value(),
            'color' => $this->color->value(),
        ];
    }

    public function equals(?self $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if (! $this->text->equals($other->text)) {
            return false;
        }

        if (! $this->color->equals($other->color)) {
            return false;
        }

        return true;
    }
}
