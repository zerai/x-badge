<?php declare(strict_types=1);

namespace Badge\Adapter\Out;

use Badge\Application\Domain\Model\Service\RepositoryReader\RepositoryDetailReader;
use Badge\Application\PortOut\CommittedFileDetector as CommittedFileDetectorPort;

final class CommittedFileDetector implements CommittedFileDetectorPort
{
    private RepositoryDetailReader $repositoryReader;

    private CommittedFileChecker $fileChecker;

    public function __construct(RepositoryDetailReader $repositoryReader, CommittedFileChecker $fileChecker)
    {
        $this->repositoryReader = $repositoryReader;
        $this->fileChecker = $fileChecker;
    }

    public function detectComposerLock(string $packageName): int
    {
        $repositoryDetail = $this->repositoryReader->readRepositoryDetail($packageName);

        return $this->fileChecker->checkFile($repositoryDetail, 'composer.lock');
    }
}
