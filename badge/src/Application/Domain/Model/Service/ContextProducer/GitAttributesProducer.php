<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;
use Badge\Application\Port\Driven\ForDetectingGitAttributesFile;

final class GitAttributesProducer implements ContextProducer
{
    private ForDetectingGitAttributesFile $committedFileDetector;

    public function __construct(ForDetectingGitAttributesFile $committedFileDetector)
    {
        $this->committedFileDetector = $committedFileDetector;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        $gitAttributesFileStatusCode = $this->committedFileDetector->detectGitAttributes($packageName);

        return $this->createFromCommittedFileStatus($gitAttributesFileStatusCode);
    }

    private function createFromCommittedFileStatus(int $committedFileStatus): BadgeContext
    {
        if ($committedFileStatus === 200) {
            return GitAttributesFile::createAsCommitted();
        }

        if ($committedFileStatus === 404) {
            return GitAttributesFile::createAsUncommitted();
        }

        return GitAttributesFile::createAsError();
    }
}
