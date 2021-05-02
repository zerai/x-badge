<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Application\BadgeImage;
use Badge\Application\Image;
use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Application\Usecase\TotalDownloadsBadgeGenerator */
final class TotalDownloadsUseCaseTest extends AcceptanceTestCase
{
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
    public function createATotalDownloadsBadgeForAPackage(): void
    {
        PackagistBuilder::withVendorAndProjectName('doctrine', 'collections')
            ->addBitbucketAsHostingServiceProvider()
            ->addTotalDownloads(100)
            ->build();

        $result = $this->application->createTotalDownloadsBadge('doctrine/collections');

        self::assertTrue(self::badgeImageHasColor(self::COLOR, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasSubjectValue('100', $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveAn404HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'unkwown-project')
            ->addBitbucketAsHostingServiceProvider()
            ->addTotalDownloads(50000)
            ->addHttpStatusCode(404)
            ->build();

        $result = $this->application->createTotalDownloadsBadge('notexist/unkwown-project');

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
            ->addTotalDownloads(5768500)
            ->addHttpStatusCode(500)
            ->build();

        $result = $this->application->createTotalDownloadsBadge('notexist/unkwown-project');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createATotalDownloadsBadgeForAPackageWithZeroTotalDownloads(): void
    {
        PackagistBuilder::withVendorAndProjectName('phpunit', 'package-with-zero-total-downloads')
            ->addGithubAsHostingServiceProvider()
            ->addTotalDownloads(0)
            ->build();

        $result = $this->application->createTotalDownloadsBadge('phpunit/package-with-zero-total-downloads');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
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

    public static function badgeImageHasSubjectValue(string $subjectValue, Image $image): bool
    {
        self::assertStringContainsString(
            $subjectValue,
            $image->getContent(),
            \sprintf('Error subject-value %s not found in badge image.', $subjectValue)
        );

        return true;
    }
}
