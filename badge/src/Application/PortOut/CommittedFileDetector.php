<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\ForDetectingComposerLockFile;
use Badge\Application\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\ForDetectingGitAttributesFile;

interface CommittedFileDetector extends ForDetectingComposerLockFile, ForDetectingGitAttributesFile
{
}
