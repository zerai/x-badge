<?php declare(strict_types=1);

namespace Badge\Infrastructure;

use Badge\Adapter\Out\CommittedFileChecker;
use Badge\Adapter\Out\DefaultBranchDetector;
use Badge\Adapter\Out\PackagistContextValueReader;

use Badge\AdapterForReadingRepositoryDetail\PackagistRepositoryReader;
use Badge\Application\BadgeApplication;
use Badge\Application\BadgeApplicationInterface;
use Badge\Application\Domain\Model\Service\ContextProducer\CommittedFileDetector;
use Badge\Application\Domain\Model\Service\ContextProducer\ComposerLockProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\DailyDownloadsProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\DependentsProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\GitAttributesProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\LicenseProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\MonthlyDownloadsProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\StableVersionProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\SuggestersProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\TotalDownloadsProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\UnstableVersionProducer;
use Badge\Application\ImageFactory;
use Badge\Application\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\ForDetectingRepositoryBranch;
use Badge\Application\Port\Driven\ForReadingRepositoryDetail\ForReadingRepositoryDetail;
use Badge\Application\Usecase\ComposerLockBadgeGenerator;
use Badge\Application\Usecase\DailyDownloadsBadgeGenerator;
use Badge\Application\Usecase\DependentsBadgeGenerator;
use Badge\Application\Usecase\GitattributesBadgeGenerator;
use Badge\Application\Usecase\LicenseBadgeGenerator;
use Badge\Application\Usecase\MonthlyDownloadsBadgeGenerator;
use Badge\Application\Usecase\StableVersionBadgeGenerator;
use Badge\Application\Usecase\SuggestersBadgeGenerator;
use Badge\Application\Usecase\TotalDownloadsBadgeGenerator;
use Badge\Application\Usecase\UnstableVersionBadgeGenerator;
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

    protected ?ForReadingRepositoryDetail $repositoryDetailReader = null;

    protected ?ForDetectingRepositoryBranch $defaultBranchDetector = null;

    protected ?CommittedFileChecker $committedFileChecker = null;

    protected ?CommittedFileDetector $committedFileDetector = null;

    protected ?ComposerLockProducer $composerLockProducer = null;

    protected ?ComposerLockBadgeGenerator $composerLockUseCase = null;

    protected ?GitAttributesProducer $gitattributesProducer = null;

    protected ?GitattributesBadgeGenerator $gitattributesUseCase = null;

    protected ?PackagistContextValueReader $contextValueReader = null;

    protected ?SuggestersProducer $suggestersProducer = null;

    protected ?SuggestersBadgeGenerator $suggestersUseCase = null;

    protected ?DependentsProducer $dependentsProducer = null;

    protected ?DependentsBadgeGenerator $dependentsUseCase = null;

    protected ?TotalDownloadsProducer $totalDownloadsProducer = null;

    protected ?TotalDownloadsBadgeGenerator $totalDownloadsUseCase = null;

    protected ?MonthlyDownloadsProducer $monthlyDownloadsProducer = null;

    protected ?MonthlyDownloadsBadgeGenerator $monthlyDownloadsUseCase = null;

    protected ?DailyDownloadsProducer $dailyDownloadsProducer = null;

    protected ?DailyDownloadsBadgeGenerator $dailyDownloadsUseCase = null;

    protected ?StableVersionProducer $stableVersionProducer = null;

    protected ?StableVersionBadgeGenerator $stableVersionUseCase = null;

    protected ?UnstableVersionProducer $unstableVersionProducer = null;

    protected ?UnstableVersionBadgeGenerator $unstableVersionUseCase = null;

    protected ?LicenseProducer $licenseProducer = null;

    protected ?LicenseBadgeGenerator $licenseUseCase = null;

    public function application(): BadgeApplicationInterface
    {
        if ($this->application === null) {
            $this->application = new BadgeApplication(
                $this->composerLockUseCase(),
                $this->gitattributesUseCase(),
                $this->suggestersUseCase(),
                $this->dependentsUseCase(),
                $this->totalDownloadsUseCase(),
                $this->monthlyDownloadsUseCase(),
                $this->dailyDownloadsUseCase(),
                $this->stableVersionUseCase(),
                $this->unstableVersionUseCase(),
                $this->licenseUseCase(),
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

    protected function repositoryDetailReader(): ForReadingRepositoryDetail
    {
        if ($this->repositoryDetailReader === null) {
            $this->repositoryDetailReader = new PackagistRepositoryReader(
                $this->packagistApiClient()
            );
        }

        return $this->repositoryDetailReader;
    }

    protected function defaultBranchDetector(): ForDetectingRepositoryBranch
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

    protected function gitattributesProducer(): GitAttributesProducer
    {
        if ($this->gitattributesProducer === null) {
            $this->gitattributesProducer = new GitAttributesProducer(
                $this->committedFileDetector()
            );
        }

        return $this->gitattributesProducer;
    }

    protected function gitattributesUseCase(): GitattributesBadgeGenerator
    {
        if ($this->gitattributesUseCase === null) {
            $this->gitattributesUseCase = new GitattributesBadgeGenerator(
                $this->gitattributesProducer(),
                $this->imageFactory()
            );
        }

        return $this->gitattributesUseCase;
    }

    protected function contextValueReader(): PackagistContextValueReader
    {
        if ($this->contextValueReader === null) {
            $this->contextValueReader = new PackagistContextValueReader(
                $this->packagistApiClient()
            );
        }

        return $this->contextValueReader;
    }

    protected function suggestersProducer(): SuggestersProducer
    {
        if ($this->suggestersProducer === null) {
            $this->suggestersProducer = new SuggestersProducer(
                $this->contextValueReader()
            );
        }

        return $this->suggestersProducer;
    }

    protected function suggestersUseCase(): SuggestersBadgeGenerator
    {
        if ($this->suggestersUseCase === null) {
            $this->suggestersUseCase = new SuggestersBadgeGenerator(
                $this->suggestersProducer(),
                $this->imageFactory()
            );
        }

        return $this->suggestersUseCase;
    }

    protected function dependentsProducer(): DependentsProducer
    {
        if ($this->dependentsProducer === null) {
            $this->dependentsProducer = new DependentsProducer(
                $this->contextValueReader()
            );
        }

        return $this->dependentsProducer;
    }

    protected function dependentsUseCase(): DependentsBadgeGenerator
    {
        if ($this->dependentsUseCase === null) {
            $this->dependentsUseCase = new DependentsBadgeGenerator(
                $this->dependentsProducer(),
                $this->imageFactory()
            );
        }

        return $this->dependentsUseCase;
    }

    protected function totalDownloadsProducer(): TotalDownloadsProducer
    {
        if ($this->totalDownloadsProducer === null) {
            $this->totalDownloadsProducer = new TotalDownloadsProducer(
                $this->contextValueReader()
            );
        }

        return $this->totalDownloadsProducer;
    }

    protected function totalDownloadsUseCase(): TotalDownloadsBadgeGenerator
    {
        if ($this->totalDownloadsUseCase === null) {
            $this->totalDownloadsUseCase = new TotalDownloadsBadgeGenerator(
                $this->totalDownloadsProducer(),
                $this->imageFactory()
            );
        }

        return $this->totalDownloadsUseCase;
    }

    protected function monthlyDownloadsProducer(): MonthlyDownloadsProducer
    {
        if ($this->monthlyDownloadsProducer === null) {
            $this->monthlyDownloadsProducer = new MonthlyDownloadsProducer(
                $this->contextValueReader()
            );
        }

        return $this->monthlyDownloadsProducer;
    }

    protected function monthlyDownloadsUseCase(): MonthlyDownloadsBadgeGenerator
    {
        if ($this->monthlyDownloadsUseCase === null) {
            $this->monthlyDownloadsUseCase = new MonthlyDownloadsBadgeGenerator(
                $this->monthlyDownloadsProducer(),
                $this->imageFactory()
            );
        }

        return $this->monthlyDownloadsUseCase;
    }

    protected function dailyDownloadsProducer(): DailyDownloadsProducer
    {
        if ($this->dailyDownloadsProducer === null) {
            $this->dailyDownloadsProducer = new DailyDownloadsProducer(
                $this->contextValueReader()
            );
        }

        return $this->dailyDownloadsProducer;
    }

    protected function dailyDownloadsUseCase(): DailyDownloadsBadgeGenerator
    {
        if ($this->dailyDownloadsUseCase === null) {
            $this->dailyDownloadsUseCase = new DailyDownloadsBadgeGenerator(
                $this->dailyDownloadsProducer(),
                $this->imageFactory()
            );
        }

        return $this->dailyDownloadsUseCase;
    }

    protected function stableVersionProducer(): StableVersionProducer
    {
        if ($this->stableVersionProducer === null) {
            $this->stableVersionProducer = new StableVersionProducer(
                $this->contextValueReader()
            );
        }

        return $this->stableVersionProducer;
    }

    protected function stableVersionUseCase(): StableVersionBadgeGenerator
    {
        if ($this->stableVersionUseCase === null) {
            $this->stableVersionUseCase = new StableVersionBadgeGenerator(
                $this->stableVersionProducer(),
                $this->imageFactory()
            );
        }

        return $this->stableVersionUseCase;
    }

    protected function unstableVersionProducer(): UnstableVersionProducer
    {
        if ($this->unstableVersionProducer === null) {
            $this->unstableVersionProducer = new UnstableVersionProducer(
                $this->contextValueReader()
            );
        }

        return $this->unstableVersionProducer;
    }

    protected function unstableVersionUseCase(): UnstableVersionBadgeGenerator
    {
        if ($this->unstableVersionUseCase === null) {
            $this->unstableVersionUseCase = new UnstableVersionBadgeGenerator(
                $this->unstableVersionProducer(),
                $this->imageFactory()
            );
        }

        return $this->unstableVersionUseCase;
    }

    protected function licenseProducer(): LicenseProducer
    {
        if ($this->licenseProducer === null) {
            $this->licenseProducer = new LicenseProducer(
                $this->contextValueReader()
            );
        }

        return $this->licenseProducer;
    }

    protected function licenseUseCase(): LicenseBadgeGenerator
    {
        if ($this->licenseUseCase === null) {
            $this->licenseUseCase = new LicenseBadgeGenerator(
                $this->licenseProducer(),
                $this->imageFactory()
            );
        }

        return $this->licenseUseCase;
    }

    abstract protected function packagistApiClient(): PackagistClient;

    abstract protected function githubApiClient(): GithubClient;

    abstract protected function bitbucketApiClient(): BitbucketClient;

    abstract protected function httpClient(): GuzzleHttpClient;
}
