<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\Common\BaseCount;

final class MonthlyDownloads extends BaseCount implements BadgeContext
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'downloads';

    private const SUFFIX = 'this month';

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

    protected function suffix(): string
    {
        return $this->suffix;
    }
}
