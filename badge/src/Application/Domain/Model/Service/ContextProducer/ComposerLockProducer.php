<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use Badge\Application\Port\Driven\ForDetectingComposerLockFile;

final class ComposerLockProducer implements ContextProducer
{
    private ForDetectingComposerLockFile $committedFileDetector;

    public function __construct(ForDetectingComposerLockFile $committedFileDetector)
    {
        $this->committedFileDetector = $committedFileDetector;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        $composerLockFileStatusCode = $this->committedFileDetector->detectComposerLock($packageName);

        return $this->createFromCommittedFileStatus($composerLockFileStatusCode);
    }

    private function createFromCommittedFileStatus(int $committedFileStatus): BadgeContext
    {
        if ($committedFileStatus === 200) {
            return ComposerLockFile::createAsCommitted();
        }

        if ($committedFileStatus === 404) {
            return ComposerLockFile::createAsUncommitted();
        }

        return ComposerLockFile::createAsError();
    }
}
