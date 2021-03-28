<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use Badge\Application\Domain\Model\RenderableValue;

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

    public function contextFor(string $packageName): RenderableValue
    {
        try {
            $composerLockFileStatusCode = $this->committedFileDetector->detectComposerLock($packageName);

            return $this->createFromFileStatusCode($composerLockFileStatusCode);
        } catch (\Throwable $th) {
            // log exception
            throw $th;
        }
    }

    private function createFromFileStatusCode(int $fileStatusCode): RenderableValue
    {
        if ($fileStatusCode === 200) {
            return ComposerLockFile::createAsCommitted();
        }

        if ($fileStatusCode === 404) {
            return ComposerLockFile::createAsUncommitted();
        }

        return ComposerLockFile::createAsError();
    }
}
