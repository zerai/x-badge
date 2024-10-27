<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\ContextValue;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\Common\BaseCount;

final class DailyDownloads extends BaseCount implements BadgeContext
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'downloads';

    private const SUFFIX = 'today';

    public static function withCount(int $value): self
    {
        return new self($value, self::SUFFIX);
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
