<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue;

use Badge\Application\Domain\Model\ContextValue\Common\CommittedFile;

final class GitAttributesFile extends CommittedFile
{
    public static function createAsCommitted(): self
    {
        return new self('committed');
    }

    public static function createAsUncommitted(): self
    {
        return new self('uncommitted');
    }

    public static function createAsUndetected(): self
    {
        return new self('uncommitted');
    }
}
