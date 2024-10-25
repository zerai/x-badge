<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Domain\Model\Service\ContextProducer\ForDetectingComposerLockFile;
use Badge\Application\Domain\Model\Service\ContextProducer\ForDetectingGitAttributesFile;

interface CommittedFileDetector extends ForDetectingComposerLockFile, ForDetectingGitAttributesFile
{
}
