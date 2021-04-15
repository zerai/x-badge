<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension\Assertions;

use PHPUnit\Framework\Constraint\Constraint;

final class IsCommittedGitAttributesFileBadgeContext extends Constraint
{
    private const SUBJECT = '.gitattributes';

    private const GITATTRIBUTES_COMMITTED = 'committed';

    private const COLOR_COMMITTED = '#96d490';

    public function toString(): string
    {
        return 'is committed GitAttributes BadgeContext.';
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

        if ($other['subject-value'] !== self::GITATTRIBUTES_COMMITTED) {
            return false;
        }

        if ($other['color'] !== self::COLOR_COMMITTED) {
            return false;
        }

        return true;
    }
}
