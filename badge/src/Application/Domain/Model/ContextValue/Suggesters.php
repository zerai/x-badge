<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\Common\BaseCount;

final class Suggesters extends BaseCount implements BadgeContext
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'suggesters';

    public static function withCount(int $value)
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
