<?php declare(strict_types=1);

namespace Badge\Core\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile;

interface CommittedFileDetector extends ForDetectingComposerLockFile, ForDetectingGitAttributesFile
{
}
