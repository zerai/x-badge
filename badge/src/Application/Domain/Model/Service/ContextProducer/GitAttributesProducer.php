<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;
use Badge\Application\Domain\Model\RenderableValue;

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

    public function contextFor(string $packageName): RenderableValue
    {
        try {
            $gitAttributesFileStatusCode = $this->committedFileDetector->detectGitAttributes($packageName);

            return $this->createFromFileStatusCode($gitAttributesFileStatusCode);
        } catch (\Throwable $th) {
            // log exception
            throw $th;
        }
    }

    private function createFromFileStatusCode(int $fileStatusCode): RenderableValue
    {
        if ($fileStatusCode === 200) {
            return GitAttributesFile::createAsCommitted();
        }

        if ($fileStatusCode === 404) {
            return GitAttributesFile::createAsUncommitted();
        }

        return GitAttributesFile::createAsError();
    }
}
