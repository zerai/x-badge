<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\CommittedFileChecker;
use Badge\Application\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\CommittedFileDetector as CommittedFileDetectorPort;

use Badge\Application\Port\Driven\ForReadingRepositoryDetail\ForReadingRepositoryDetail;

final class CommittedFileDetector implements CommittedFileDetectorPort
{
    private const COMPOSERLOCK_FILE = 'composer.lock';

    private const GITATTRIBUTES_FILE = '.gitattributes';

    private ForReadingRepositoryDetail $repositoryReader;

    private CommittedFileChecker $fileChecker;

    public function __construct(ForReadingRepositoryDetail $repositoryReader, CommittedFileChecker $fileChecker)
    {
        $this->repositoryReader = $repositoryReader;
        $this->fileChecker = $fileChecker;
    }

    public function detectComposerLock(string $packageName): int
    {
        $repositoryDetail = $this->repositoryReader->readRepositoryDetail($packageName);

        return $this->fileChecker->checkFile($repositoryDetail, self::COMPOSERLOCK_FILE);
    }

    public function detectGitAttributes(string $packageName): int
    {
        $repositoryDetail = $this->repositoryReader->readRepositoryDetail($packageName);

        return $this->fileChecker->checkFile($repositoryDetail, self::GITATTRIBUTES_FILE);
    }
}
