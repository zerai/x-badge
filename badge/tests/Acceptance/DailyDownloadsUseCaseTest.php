<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Core\BadgeImage;
use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Core\Usecase\DailyDownloadsBadgeGenerator */
final class DailyDownloadsUseCaseTest extends AcceptanceTestCase
{
    use BadgeImageAssertionsTrait;

    private const COLOR = '007ec6';

    private const SUBJECT = 'downloads';

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
    public function createADailyDownloadsBadgeForAPackage(): void
    {
        PackagistBuilder::withVendorAndProjectName('badges', 'poser')
            ->addBitbucketAsHostingServiceProvider()
            ->addDailyDownloads(100)
            ->build();

        $result = $this->application->createDailyDownloadsBadge('badges/poser');

        self::assertTrue(self::badgeImageHasColor(self::COLOR, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasSubjectValue('100', $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetieveAn404HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'unkwown-project')
            ->addBitbucketAsHostingServiceProvider()
            ->addDailyDownloads(500)
            ->addHttpStatusCode(404)
            ->build();

        $result = $this->application->createDailyDownloadsBadge('notexist/unkwown-project');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetieveAn500HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'unkwown-project')
            ->addBitbucketAsHostingServiceProvider()
            ->addDailyDownloads(2050)
            ->addHttpStatusCode(500)
            ->build();

        $result = $this->application->createDailyDownloadsBadge('notexist/unkwown-project');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createADailyDownloadsBadgeForAPackageWithZeroDailyDownloads(): void
    {
        PackagistBuilder::withVendorAndProjectName('irrelevantvendor', 'package-with-zero-daily-downloads')
            ->addBitbucketAsHostingServiceProvider()
            ->addDailyDownloads(0)
            ->build();

        $result = $this->application->createDailyDownloadsBadge('irrelevantvendor/package-with-zero-daily-downloads');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
    }
}
