<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension\Assertions;

use PHPUnit\Framework\Constraint\Constraint;

final class IsUncommittedGitAttributesFileBadgeContext extends Constraint
{
    private const SUBJECT = '.gitattributes';

    private const GITATTRIBUTES_UNCOMMITTED = 'uncommitted';

    private const COLOR_UNCOMMITTED = '#ad6c4b';

    public function toString(): string
    {
        return 'is uncommitted GitAttributes BadgeContext.';
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

        if ($other['subject-value'] !== self::GITATTRIBUTES_UNCOMMITTED) {
            return false;
        }

        if ($other['color'] !== self::COLOR_UNCOMMITTED) {
            return false;
        }

        return true;
    }
}
