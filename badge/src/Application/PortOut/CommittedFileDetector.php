<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Domain\Model\Service\ContextProducer\ForDetectingGitAttributesFile;
use Badge\Application\Port\Driven\ForDetectingComposerLockFile;

interface CommittedFileDetector extends ForDetectingComposerLockFile, ForDetectingGitAttributesFile
{
}
