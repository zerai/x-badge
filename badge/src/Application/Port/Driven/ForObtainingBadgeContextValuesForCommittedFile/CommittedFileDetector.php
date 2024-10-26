<?php declare(strict_types=1);

namespace Badge\Application\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile;

interface CommittedFileDetector extends ForDetectingComposerLockFile, ForDetectingGitAttributesFile
{
}
