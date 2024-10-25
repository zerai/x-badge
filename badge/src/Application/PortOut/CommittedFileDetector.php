<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Domain\Model\Service\ContextProducer\ForDetectingComposerLockFile;
use Badge\Application\Domain\Model\Service\ContextProducer\DetectableGitAttributes;

interface CommittedFileDetector extends ForDetectingComposerLockFile, DetectableGitAttributes
{
}
