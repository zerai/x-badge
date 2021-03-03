<?php

declare(strict_types=1);

namespace Badge\Tests\Discovery\VO;

/**
 * null
 * @codeCoverageIgnore
 */
final class BadgeContext
{
    private Subject $subject;

    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
    }

    public function subject(): Subject
    {
        return $this->subject;
    }

    public function withSubject(Subject $subject): self
    {
        return new self(
            $subject
        );
    }

    public static function fromArray(array $data): self
    {
        if (! isset($data['subject']) || ! \is_string($data['subject'])) {
            throw new \InvalidArgumentException('Error on "subject", string expected');
        }

        return new self(
            new Subject($data['subject']),
        );
    }

    public function toArray(): array
    {
        return [
            'subject' => $this->subject->value(),
        ];
    }

    public function equals(?self $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if (! $this->subject->equals($other->subject)) {
            return false;
        }

        return true;
    }
}
