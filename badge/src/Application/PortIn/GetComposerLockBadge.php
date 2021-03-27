<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

interface GetComposerLockBadge
{
    public function getComposerLockBadge(string $packageName);
}
