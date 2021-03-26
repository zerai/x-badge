<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;

final class GitAttributesProducer implements ContextProducer
{
    /**
     * @var DetectableGitAttributes
     */
    private $committedFileDetector;

    public function __construct(DetectableGitAttributes $committedFileDetector)
    {
        $this->committedFileDetector = $committedFileDetector;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        try {
            $gitAttributesFileStatusCode = $this->committedFileDetector->detectGitAttributes($packageName);

            return $this->createFromFileStatusCode($gitAttributesFileStatusCode);
        } catch (\Throwable $th) {
            // log exception
            return BadgeContext::asDefault();
        }
    }

    private function createFromFileStatusCode(int $fileStatusCode): BadgeContext
    {
        if ($fileStatusCode === 200) {
            return BadgeContext::fromContextValue(GitAttributesFile::createAsCommitted());
        }

        if ($fileStatusCode === 404) {
            return BadgeContext::fromContextValue(GitAttributesFile::createAsUncommitted());
        }

        return BadgeContext::fromContextValue(GitAttributesFile::createAsError());
    }
}
