<?php declare(strict_types=1);

namespace Badge\Application\Domain\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\DetectableComposerLock;

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
        } catch (\Throwable $th) {
            //throw for http error on packagist
            //throw for http error on GithubApi or BitBucketApi
            //throw for http error on checking file in repository sourcecode
        }
    }
}
