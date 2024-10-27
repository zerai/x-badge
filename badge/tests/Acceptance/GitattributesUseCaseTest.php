<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Core\BadgeImage;
use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\CommittedFileBuilder\CommittedFileBuilder;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Core\Usecase\GitattributesBadgeGenerator */
final class GitattributesUseCaseTest extends AcceptanceTestCase
{
    use BadgeImageAssertionsTrait;

    private const COLOR_COMMITTED = '#96d490';

    private const COLOR_UNCOMMITTED = '#ad6c4b';

    private const COLOR_ERROR = '#aa0000';

    private const GITATTRIBUTES_COMMITTED = 'committed';

    private const GITATTRIBUTES_UNCOMMITTED = 'uncommitted';

    private const GITATTRIBUTES_ERROR = 'checking';

    private const SUBJECT = '.gitattributes';

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
    public function createAGitattributesBadgeForCommittedFileHostedOnGithub(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addGitattributeFileWithHttpStatusCode(200)
            ->build();

        $result = $this->application->createGitattributesBadge('vendorname/projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_COMMITTED, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::GITATTRIBUTES_COMMITTED, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForCommittedFileHostedOnBitbuket(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'bitbuket-projectname')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('bitbucketmaster')
            ->addGitattributeFileWithHttpStatusCode(200)
            ->build();

        $result = $this->application->createGitattributesBadge('vendorname/bitbuket-projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_COMMITTED, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::GITATTRIBUTES_COMMITTED, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForUncommittedFileHostedOnGithub(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addGitattributeFileWithHttpStatusCode(404)
            ->build();

        $result = $this->application->createGitattributesBadge('vendorname/projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_UNCOMMITTED, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::GITATTRIBUTES_UNCOMMITTED, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForUncommittedFileHostedOnBitbucket(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'bitbucket-projectname')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addGitattributeFileWithHttpStatusCode(404)
            ->build();

        $result = $this->application->createGitattributesBadge('vendorname/bitbucket-projectname');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_UNCOMMITTED, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::GITATTRIBUTES_UNCOMMITTED, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForErrorCheckingHostedOnGithub(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname-with-error')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addGitattributeFileWithHttpStatusCode(500)
            ->build();

        $result = $this->application->createGitattributesBadge('vendorname/projectname-with-error');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_ERROR, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_ERROR, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::GITATTRIBUTES_ERROR, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForErrorCheckingHostedOnBitbucket(): void
    {
        CommittedFileBuilder::withVendorAndProjectName('vendorname', 'bitbucket-projectname-with-error')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('bitbucketmaster')
            ->addGitattributeFileWithHttpStatusCode(500)
            ->build();

        $result = $this->application->createGitattributesBadge('vendorname/bitbucket-projectname-with-error');

        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_ERROR, $result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_ERROR, $result));
        self::assertTrue(self::badgeImageHasSubjectValue(self::GITATTRIBUTES_ERROR, $result));
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

        $result = $this->application->createGitattributesBadge('notexist/unkwown-project');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }
}
