<?php declare(strict_types=1);

namespace Badge\Application;

class BadgeImage implements Image
{
    private string $name;

    private string $content;

    private function __construct(string $name, string $content)
    {
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
}
