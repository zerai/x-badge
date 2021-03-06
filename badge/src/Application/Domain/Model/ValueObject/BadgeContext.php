<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ValueObject;

use Badge\Application\Domain\Model\DefaultBadgeConfig;

final class BadgeContext
{
    private Subject $subject;

    private SubjectValue $subjectValue;

    public function __construct(Subject $subject, SubjectValue $subjectValue)
    {
        $this->subject = $subject;
        $this->subjectValue = $subjectValue;
    }

    public static function asDefault(): self
    {
        return new self(
            new Subject(DefaultBadgeConfig::SUBJECT),
            SubjectValue::fromArray([
                'text' => DefaultBadgeConfig::SUBJECT_VALUE,
                'color' => DefaultBadgeConfig::COLOR,
            ])
        );
    }

    public function subject(): Subject
    {
        return $this->subject;
    }

    public function subjectValue(): SubjectValue
    {
        return $this->subjectValue;
    }

    /**
     * @param array{'subject': string, 'subject-value': string, "color": string} $data
     */
    public static function fromArray(array $data): self
    {
        if (! isset($data['subject'])) {
            throw new \InvalidArgumentException('Error on "subject", string expected');
        }

        if (! isset($data['subject-value'])) {
            throw new \InvalidArgumentException('Error on "subject value", string expected');
        }

        if (! isset($data['color'])) {
            throw new \InvalidArgumentException('Error on "color", string expected');
        }

        return new self(
            new Subject($data['subject']),
            new SubjectValue(
                new Text($data['subject-value']),
                new Color($data['color'])
            )
        );
    }

    /**
     * @return array{'subject': string, 'subject-value': string, "color": string} $data
     */
    public function toArray(): array
    {
        return [
            'subject' => $this->subject->value(),
            'subject-value' => $this->subjectValue->text()->value(),
            'color' => $this->subjectValue->color()->value(),
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

        if (! $this->subjectValue->equals($other->subjectValue)) {
            return false;
        }

        return true;
    }
}
