<?php declare(strict_types=1);

namespace Badge\Core;

use InvalidArgumentException;

class BadgeImage implements Image
{
    private string $name;

    private string $content;

    private function __construct(string $name, string $content)
    {
        $this->validate($name, $content);
        $this->name = $name;
        $this->content = $content;
    }

    /**
     * Returns the image content as binary string.
     */
    public function __toString(): string
    {
        return $this->content;
    }

    public static function create(string $name, string $content): self
    {
        return new self($name, $content);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getFileName(): string
    {
        return $this->name;
    }

    private function validate(string $name, string $content): void
    {
        if ($name === '') {
            throw new InvalidArgumentException('Error: image with empty name not allowed');
        }
        if ($content === '') {
            throw new InvalidArgumentException('Error: image with empty content not allowed');
        }
        if (! preg_match('/^[a-zA-Z0-9-]+\.svg$/', $name)) {
            throw new InvalidArgumentException(sprintf('Error: %s is not a valid filename', $name));
        }
    }
}
