<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\BadgeContext;

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

    public function color(): Color
    {
        return $this->color;
    }

    /**
     * @param array{text: string, color: string} $data
     */
    public static function fromArray(array $data): self
    {
        if (! isset($data['text'])) {
            throw new \InvalidArgumentException('Error on "text", string expected');
        }

        if (! isset($data['color'])) {
            throw new \InvalidArgumentException('Error on "color", string expected');
        }

        return new self(
            new Text($data['text']),
            new Color($data['color']),
        );
    }

    public function equals(self $other): bool
    {
        if (! $this->text->equals($other->text)) {
            return false;
        }

        if (! $this->color->equals($other->color)) {
            return false;
        }

        return true;
    }
}
