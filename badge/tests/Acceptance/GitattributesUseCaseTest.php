<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Application\BadgeImage;
use Badge\Application\Image;
use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\CommittedFileBuilder\CommittedFileBuilder;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Application\Usecase\GitattributesBadgeGenerator */
final class GitattributesUseCaseTest extends AcceptanceTestCase
{
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
        self::markTestSkipped('Fix builder');
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addGitattributeFileWithHttpStatusCode(200);

        $builder->build();

        $result = $this->application->createGitattributesBadge('vendorname/projectname');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForCommittedFileHostedOnBitbuket(): void
    {
        self::markTestSkipped('Fix builder');
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'bitbuket-projectname')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('bitbucketmaster')
            ->addGitattributeFileWithHttpStatusCode(200);

        $builder->build();

        $result = $this->application->createGitattributesBadge('vendorname/bitbuket-projectname');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForUncommittedFileHostedOnGithub(): void
    {
        self::markTestSkipped('Fix builder');
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addGitattributeFileWithHttpStatusCode(404);

        $builder->build();

        $result = $this->application->createGitattributesBadge('vendorname/projectname');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForUncommittedFileHostedOnBitbucket(): void
    {
        self::markTestSkipped('Fix builder');
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'bitbucket-projectname')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addGitattributeFileWithHttpStatusCode(404);

        $builder->build();

        $result = $this->application->createGitattributesBadge('vendorname/bitbucket-projectname');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForErrorCheckingHostedOnGithub(): void
    {
        self::markTestSkipped('Error');
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname-with-error')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addGitattributeFileWithHttpStatusCode(500);

        $builder->build();

        $result = $this->application->createGitattributesBadge('vendorname/projectname-with-error');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_ERROR, $result));
    }

    /**
     * @test
     */
    public function createAGitattributesBadgeForErrorCheckingHostedOnBitbucket(): void
    {
        self::markTestSkipped('Error');
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'bitbucket-projectname-with-error')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('bitbucketmaster')
            ->addGitattributeFileWithHttpStatusCode(500);

        $builder->build();

        $result = $this->application->createGitattributesBadge('vendorname/bitbucket-projectname-with-error');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_ERROR, $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetieveAn404HttpErrorFromPackagist(): void
    {
        self::markTestSkipped('Fix builder');
        PackagistBuilder::withVendorAndProjectName('notexist', 'unkwown-project')
            ->addBitbucketAsHostingServiceProvider()
            ->addDailyDownloads(500)
            ->addHttpStatusCode(404)
            ->build();

        $result = $this->application->createGitattributesBadge('notexist/unkwown-project');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    public static function isDefaultBadgeImage(Image $value): bool
    {
        if ($value->getFileName() === 'default-badge.svg') {
            return true;
        }

        return false;
    }

    public static function badgeImageHasColor(string $color, Image $image): bool
    {
        self::assertStringContainsString(
            $color,
            $image->getContent(),
            \sprintf('Error color %s not found in badge image.', $color)
        );

        return true;
    }

    public static function badgeImageHasSubject(string $subject, Image $image): bool
    {
        self::assertStringContainsString(
            $subject,
            $image->getContent(),
            \sprintf('Error subject %s not found in badge image.', $subject)
        );

        return true;
    }
}
