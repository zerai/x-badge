<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Port\Driven\ForDetectingComposerLockFile;
use Badge\Application\Port\Driven\ForDetectingGitAttributesFile;

interface CommittedFileDetector extends ForDetectingComposerLockFile, ForDetectingGitAttributesFile
{
}
