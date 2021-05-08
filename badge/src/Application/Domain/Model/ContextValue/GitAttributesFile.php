<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\Common\CommittedFile;

final class GitAttributesFile extends CommittedFile implements BadgeContext
{
    private const COLOR_COMMITTED = '#96d490';

    private const COLOR_UNCOMMITTED = '#ad6c4b';

    private const COLOR_ERROR = '#aa0000';

    private const GITATTRIBUTES_COMMITTED = 'committed';

    private const GITATTRIBUTES_UNCOMMITTED = 'uncommitted';

    private const GITATTRIBUTES_ERROR = 'checking';

    private const SUBJECT = '.gitattributes';

    private const SUBJECT_ERROR = 'Error';

    public static function createAsCommitted(): self
    {
        return new self(self::GITATTRIBUTES_COMMITTED);
    }

    public static function createAsUncommitted(): self
    {
        return new self(self::GITATTRIBUTES_UNCOMMITTED);
    }

    public static function createAsError(): self
    {
        return new self(self::GITATTRIBUTES_ERROR);
    }

    /**
     * @return string[]
     *
     * @psalm-return array{subject: string, subject-value: string, color: string}
     */
    public function renderingProperties(): array
    {
        $subject = self::SUBJECT_ERROR;
        $color = self::COLOR_ERROR;

        if ($this->asBadgeValue() === self::GITATTRIBUTES_COMMITTED) {
            $subject = self::SUBJECT;
            $color = self::COLOR_COMMITTED;
        }

        if ($this->asBadgeValue() === self::GITATTRIBUTES_UNCOMMITTED) {
            $subject = self::SUBJECT;
            $color = self::COLOR_UNCOMMITTED;
        }

        return [
            'subject' => $subject,
            'subject-value' => $this->asBadgeValue(),
            'color' => $color,
        ];
    }
}
