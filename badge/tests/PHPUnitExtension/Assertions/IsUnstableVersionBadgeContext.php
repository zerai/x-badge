<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension\Assertions;

use PHPUnit\Framework\Constraint\Constraint;

final class IsUnstableVersionBadgeContext extends Constraint
{
    private const COLOR_UNSTABLE = 'e68718';

    private const SUBJECT_UNSTABLE = 'unstable';

    public function toString(): string
    {
        return 'is unstable version BadgeContext.';
    }

    public function matches($other): bool
    {
        if (! \is_array($other)) {
            return false;
        }

        if (! \array_key_exists('subject', $other) || ! \array_key_exists('subject-value', $other) || ! \array_key_exists('color', $other)) {
            return false;
        }

        if ($other['subject'] !== self::SUBJECT_UNSTABLE) {
            return false;
        }

        if ($other['color'] !== self::COLOR_UNSTABLE) {
            return false;
        }

        return true;
    }
}
