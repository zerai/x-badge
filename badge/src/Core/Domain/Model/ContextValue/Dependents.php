<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\ContextValue;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\Common\BaseCount;

final class Dependents extends BaseCount implements BadgeContext
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'dependents';

    public static function withCount(int $value): self
    {
        return new self($value);
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
}
