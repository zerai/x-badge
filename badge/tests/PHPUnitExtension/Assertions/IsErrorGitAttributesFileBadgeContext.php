<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension\Assertions;

use PHPUnit\Framework\Constraint\Constraint;

final class IsErrorGitAttributesFileBadgeContext extends Constraint
{
    private const SUBJECT_ERROR = 'Error';

    private const GITATTRIBUTES_ERROR = 'checking';

    private const COLOR_ERROR = '#aa0000';

    public function toString(): string
    {
        return 'is GitAttributes with BadgeContext.';
    }

    public function matches($other): bool
    {
        if (! \is_array($other)) {
            return false;
        }

        if (! \array_key_exists('subject', $other) || ! \array_key_exists('subject-value', $other) || ! \array_key_exists('color', $other)) {
            return false;
        }

        if ($other['subject'] !== self::SUBJECT_ERROR) {
            return false;
        }

        if ($other['subject-value'] !== self::GITATTRIBUTES_ERROR) {
            return false;
        }

        if ($other['color'] !== self::COLOR_ERROR) {
            return false;
        }

        return true;
    }
}
