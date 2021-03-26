<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;

final class ComposerLockProducer implements ContextProducer
{
    /**
     * @var DetectableComposerLock
     */
    private $committedFileDetector;

    public function __construct(DetectableComposerLock $committedFileDetector)
    {
        $this->committedFileDetector = $committedFileDetector;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        try {
            $composerLockFileStatusCode = $this->committedFileDetector->detectComposerLock($packageName);

            return $this->createFromFileStatusCode($composerLockFileStatusCode);
        } catch (\Throwable $th) {
            // log exception
            return BadgeContext::asDefault();
        }
    }

    private function createFromFileStatusCode(int $fileStatusCode): BadgeContext
    {
        if ($fileStatusCode === 200) {
            return BadgeContext::fromContextValue(ComposerLockFile::createAsCommitted());
        }

        if ($fileStatusCode === 404) {
            return BadgeContext::fromContextValue(ComposerLockFile::createAsUncommitted());
        }

        return BadgeContext::fromContextValue(ComposerLockFile::createAsError());
    }
}
