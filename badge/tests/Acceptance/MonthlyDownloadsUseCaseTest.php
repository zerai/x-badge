<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Core\BadgeImage;
use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Core\Usecase\MonthlyDownloadsBadgeGenerator */
final class MonthlyDownloadsUseCaseTest extends AcceptanceTestCase
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
    public function createAMonthlyDownloadsBadgeForAPackage(): void
    {
        PackagistBuilder::withVendorAndProjectName('badges', 'poser')
            ->addBitbucketAsHostingServiceProvider()
            ->addMonthlyDownloads(1366)
            ->build();

        $result = $this->application->createMonthlyDownloadsBadge('badges/poser');

        self::assertTrue(self::badgeImageHasColor(self::COLOR, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasSubjectValue('1.37 k this month', $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveAn404HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'unkwown-project')
            ->addBitbucketAsHostingServiceProvider()
            ->addMonthlyDownloads(500)
            ->addHttpStatusCode(404)
            ->build();

        $result = $this->application->createMonthlyDownloadsBadge('notexist/unkwown-project');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveAn500HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'unkwown-project')
            ->addBitbucketAsHostingServiceProvider()
            ->addMonthlyDownloads(500)
            ->addHttpStatusCode(500)
            ->build();

        $result = $this->application->createMonthlyDownloadsBadge('notexist/unkwown-project');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createAMonthlyDownloadsBadgeForAPackageWithZeroTotalDownloads(): void
    {
        PackagistBuilder::withVendorAndProjectName('irrelevantvendor', 'package-with-zero-monthly-downloads')
            ->addBitbucketAsHostingServiceProvider()
            ->addMonthlyDownloads(0)
            ->build();

        $result = $this->application->createMonthlyDownloadsBadge('irrelevantvendor/package-with-zero-monthly-downloads');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
    }
}
