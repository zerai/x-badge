<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\CommittedFileBuilder\CommittedFileBuilder;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Core\Usecase\ComposerLockBadgeGenerator */
final class ComposerLockUseCaseTest extends AcceptanceTestCase
{
    use BadgeImageAssertionsTrait;

    private const COLOR_COMMITTED = '#e60073';

    private const COLOR_UNCOMMITTED = '#99004d';

    private const COLOR_ERROR = '#aa0000';

    private const LOCK_COMMITTED = 'committed';

    private const LOCK_UNCOMMITTED = 'uncommitted';

    private const LOCK_ERROR = 'checking';

    private const SUBJECT = '.lock';

    private const SUBJECT_ERROR = 'Error';

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        ApiMockServer::reset();
    }

    /**
     * @test
     */
    public function createAComposerLockBadgeForCommittedFileHostedOnGithub(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addComposerLockFileWithHttpStatusCode(200)
            ->build();

        $result = $this->application->createComposerLockBadge('vendorname/projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_COMMITTED, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::LOCK_COMMITTED, $result));
    }

    /**
     * @test
     */
    public function createAComposerLockBadgeForCommittedFileHostedOnBitbuket(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'bitbuket-projectname')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('bitbucketmaster')
            ->addComposerLockFileWithHttpStatusCode(200)
            ->build();

        $result = $this->application->createComposerLockBadge('vendorname/bitbuket-projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_COMMITTED, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::LOCK_COMMITTED, $result));
    }

    /**
     * @test
     */
    public function createAComposerLockBadgeForUncommittedFileHostedOnGithub(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addComposerLockFileWithHttpStatusCode(404)
            ->build();

        $result = $this->application->createComposerLockBadge('vendorname/projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_UNCOMMITTED, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::LOCK_UNCOMMITTED, $result));
    }

    /**
     * @test
     */
    public function createAComposerLockBadgeForUncommittedFileHostedOnBitbucket(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'bitbucket-projectname')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addComposerLockFileWithHttpStatusCode(404)
            ->build();

        $result = $this->application->createComposerLockBadge('vendorname/bitbucket-projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_UNCOMMITTED, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::LOCK_UNCOMMITTED, $result));
    }

    /**
     * @test
     */
    public function createAComposerLockBadgeForErrorCheckingHostedOnGithub(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addComposerLockFileWithHttpStatusCode(500)
            ->build();

        $result = $this->application->createComposerLockBadge('vendorname/projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_ERROR, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_ERROR, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::LOCK_ERROR, $result));
    }

    /**
     * @test
     */
    public function createAComposerLockBadgeForErrorCheckingHostedOnBitbucket(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'bitbucket-projectname')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('bitbucketmaster')
            ->addComposerLockFileWithHttpStatusCode(500)
            ->build();

        $result = $this->application->createComposerLockBadge('vendorname/bitbucket-projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_ERROR, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_ERROR, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::LOCK_ERROR, $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetieveAn404HttpErrorFromPackagist(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'unkwown-project')
            ->addBitbucketAsHostingServiceProvider()
            ->addDailyDownloads(500)
            ->addHttpStatusCode(404)
            ->build();

        $result = $this->application->createComposerLockBadge('notexist/unkwown-project');

        self::assertTrue(self::isDefaultBadgeImage($result));
    }
}
