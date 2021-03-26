<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\BadgeContext;

use Badge\Application\Domain\Model\ContextualizableValue;

final class BadgeContext
{
    public const DEFAULT_BADGE_SUBJECT = '-';

    public const DEFAULT_BADGE_SUBJECT_VALUE = '-';

    public const DEFAULT_BADGE_COLOR = '#7A7A7A';

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
            new Subject(self::DEFAULT_BADGE_SUBJECT),
            SubjectValue::fromArray([
                'text' => self::DEFAULT_BADGE_SUBJECT_VALUE,
                'color' => self::DEFAULT_BADGE_COLOR,
            ])
        );
    }

    public static function fromContextValue(ContextualizableValue $value): self
    {
        /** @phpstan-ignore-next-line */
        return self::fromArray($value->renderingProperties());
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
     * @param array{subject: string, subject-value: string, color: string} $data
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
     * @return array{subject: string, subject-value: string, color: string} $data
     */
    public function toArray(): array
    {
        return [
            'subject' => $this->subject->value(),
            'subject-value' => $this->subjectValue->text()->value(),
            'color' => $this->subjectValue->color()->value(),
        ];
    }

    public function equals(self $other): bool
    {
        if (! $this->subject->equals($other->subject)) {
            return false;
        }

        if (! $this->subjectValue->equals($other->subjectValue)) {
            return false;
        }

        return true;
    }
}
