<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\ComposerLockFile;
use Badge\Core\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\ForDetectingComposerLockFile;

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
