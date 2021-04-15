<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension\Assertions;

use PHPUnit\Framework\Constraint\Constraint;

final class IsStableVersionBadgeContext extends Constraint
{
    private const COLOR_STABLE = '28a3df';

    private const SUBJECT_STABLE = 'stable';

    private const TEXT_NO_STABLE_RELEASE = 'No Release';

    public function toString(): string
    {
        return 'is committed ComposerLockFile BadgeContext.';
    }

    public function matches($other): bool
    {
        if (! \is_array($other)) {
            return false;
        }

        if (! \array_key_exists('subject', $other) || ! \array_key_exists('subject-value', $other) || ! \array_key_exists('color', $other)) {
            return false;
        }

        if ($other['subject'] !== self::SUBJECT_STABLE) {
            return false;
        }

        if ($other['color'] !== self::COLOR_STABLE) {
            return false;
        }

        return true;
    }
}
