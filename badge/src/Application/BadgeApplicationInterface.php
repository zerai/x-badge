<?php declare(strict_types=1);

namespace Badge\Application;

use Badge\Application\PortIn\CreateDependentsBadge;
use Badge\Application\PortIn\CreateSuggestersBadge;
use Badge\Application\PortIn\GetComposerLockBadge;

interface BadgeApplicationInterface extends GetComposerLockBadge, CreateSuggestersBadge, CreateDependentsBadge
{
}
