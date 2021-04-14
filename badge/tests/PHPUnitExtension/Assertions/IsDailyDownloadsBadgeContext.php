<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension\Assertions;

use PHPUnit\Framework\Constraint\Constraint;

final class IsDailyDownloadsBadgeContext extends Constraint
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'downloads';

    private const SUFFIX = ' today';

    public function toString(): string
    {
        return 'is a DailyDownload BadgeContext.';
    }

    public function matches($other): bool
    {
        if (! \is_array($other)) {
            return false;
        }

        if (! \array_key_exists('subject', $other) || ! \array_key_exists('subject-value', $other) || ! \array_key_exists('color', $other)) {
            return false;
        }

        if ($other['subject'] !== self::SUBJECT) {
            return false;
        }

        if (\substr($other['subject-value'], -6) !== self::SUFFIX) {
            return false;
        }

        if ($other['color'] !== self::COLOR) {
            return false;
        }

        return true;
    }
}
