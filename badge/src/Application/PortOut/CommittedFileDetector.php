<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Port\Driven\ForDetectingGitAttributesFile;
use Badge\Application\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\ForDetectingComposerLockFile;

interface CommittedFileDetector extends ForDetectingComposerLockFile, ForDetectingGitAttributesFile
{
}
