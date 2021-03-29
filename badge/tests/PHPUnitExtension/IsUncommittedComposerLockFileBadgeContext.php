<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension;

use PHPUnit\Framework\Constraint\Constraint;

final class IsUncommittedComposerLockFileBadgeContext extends Constraint
{
    private const SUBJECT = '.lock';

    private const LOCK_UNCOMMITTED = 'uncommitted';

    private const COLOR_UNCOMMITTED = '#99004d';

    public function toString(): string
    {
        return 'is uncommitted ComposerLockFile BadgeContext.';
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

        if ($other['subject-value'] !== self::LOCK_UNCOMMITTED) {
            return false;
        }

        if ($other['color'] !== self::COLOR_UNCOMMITTED) {
            return false;
        }

        return true;
    }
}
