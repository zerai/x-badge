<?php declare(strict_types=1);

namespace Badge\Infrastructure;

use Badge\Adapter\Out\CommittedFileChecker;
use Badge\Adapter\Out\CommittedFileDetector;
use Badge\Adapter\Out\DefaultBranchDetector;
use Badge\Adapter\Out\PackagistRepositoryReader;
use Badge\Application\BadgeApplication;
use Badge\Application\BadgeApplicationInterface;
use Badge\Application\Domain\Model\Service\ContextProducer\ComposerLockProducer;
use Badge\Application\Domain\Model\Service\DefaultBranchDetector\DetectableBranch;
use Badge\Application\Domain\Model\Service\RepositoryReader\RepositoryDetailReader;
use Badge\Application\ImageFactory;
use Badge\Application\Usecase\ComposerLockBadgeGenerator;
use Bitbucket\Client as BitbucketClient;
use Github\Client as GithubClient;
use GuzzleHttp\ClientInterface as GuzzleHttpClient;
use Packagist\Api\Client as PackagistClient;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use PUGX\Poser\Render\SvgPlasticRender;

abstract class ServiceContainer
{
    protected ?BadgeApplicationInterface $application = null;

    protected ?ImageFactory $imageFactory = null;

    protected ?RepositoryDetailReader $repositoryDetailReader = null;

    protected ?DetectableBranch $defaultBranchDetector = null;

    protected ?CommittedFileChecker $committedFileChecker = null;

    protected ?CommittedFileDetector $committedFileDetector = null;

    protected ?ComposerLockProducer $composerLockProducer = null;

    protected ?ComposerLockBadgeGenerator $composerLockUseCase = null;

    public function application(): BadgeApplicationInterface
    {
        if ($this->application === null) {
            $this->application = new BadgeApplication(
                $this->composerLockUseCase(),
            );
        }

        return $this->application;
    }

    protected function imageFactory(): imageFactory
    {
        if ($this->imageFactory === null) {
            $poserGenerator = new Poser([
                new SvgFlatRender(),
                new SvgFlatSquareRender(),
                new SvgPlasticRender(),
            ]);

            $this->imageFactory = new PoserImageFactory(
                $poserGenerator
            );
        }

        return $this->imageFactory;
    }

    protected function repositoryDetailReader(): RepositoryDetailReader
    {
        if ($this->repositoryDetailReader === null) {
            $this->repositoryDetailReader = new PackagistRepositoryReader(
                $this->packagistApiClient()
            );
        }

        return $this->repositoryDetailReader;
    }

    protected function defaultBranchDetector(): DetectableBranch
    {
        if ($this->defaultBranchDetector === null) {
            $this->defaultBranchDetector = new DefaultBranchDetector(
                $this->githubApiClient(),
                $this->bitbucketApiClient()
            );
        }

        return $this->defaultBranchDetector;
    }

    protected function committedFileChecker(): CommittedFileChecker
    {
        if ($this->committedFileChecker === null) {
            $this->committedFileChecker = new CommittedFileChecker(
                $this->httpClient(),
                $this->defaultBranchDetector()
            );
        }

        return $this->committedFileChecker;
    }

    protected function committedFileDetector(): CommittedFileDetector
    {
        if ($this->committedFileDetector === null) {
            $this->committedFileDetector = new CommittedFileDetector(
                $this->repositoryDetailReader(),
                $this->committedFileChecker()
            );
        }

        return $this->committedFileDetector;
    }

    protected function composerLockProducer(): ComposerLockProducer
    {
        if ($this->composerLockProducer === null) {
            $this->composerLockProducer = new ComposerLockProducer(
                $this->committedFileDetector()
            );
        }

        return $this->composerLockProducer;
    }

    protected function composerLockUseCase(): ComposerLockBadgeGenerator
    {
        if ($this->composerLockUseCase === null) {
            $this->composerLockUseCase = new ComposerLockBadgeGenerator(
                $this->composerLockProducer(),
                $this->imageFactory()
            );
        }

        return $this->composerLockUseCase;
    }

    abstract protected function packagistApiClient(): PackagistClient;

    abstract protected function githubApiClient(): GithubClient;

    abstract protected function bitbucketApiClient(): BitbucketClient;

    abstract protected function httpClient(): GuzzleHttpClient;
}