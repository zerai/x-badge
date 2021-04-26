<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Application\BadgeImage;
use Badge\Application\Image;
use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Application\Usecase\StableVersionBadgeGenerator */
final class StableVersionUseCaseTest extends AcceptanceTestCase
{
    private const COLOR_STABLE = '28a3df';

    private const SUBJECT_STABLE = 'stable';

    private const TEXT_NO_STABLE_RELEASE = 'No Release';

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        ApiMockServer::reset();
        parent::tearDown();
    }

    /**
     * @test
     */
    public function createAStableVersionBadgeForAPackage(): void
    {
        PackagistBuilder::withVendorAndProjectName('badges', 'poser')
            ->addGithubAsHostingServiceProvider()
            ->addReleasedVersion('9.0.1')
            ->addReleasedVersion('10.0.1')
            ->build();

        $result = $this->application->createStableVersionBadge('badges/poser');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_STABLE, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_STABLE, $result));
        self::assertTrue(self::badgeImageHasVersion('10.0.1', $result));
        self::assertTrue(self::badgeImageHasNotVersion('9.0.1', $result));
    }

    /**
     * @test
     */
    public function createAStableVersionBadgeWithNoStableReleaseForAPackage(): void
    {
        self::markTestIncomplete('it should produce a badge with \'No stable release\'');
        PackagistBuilder::withVendorAndProjectName('badges', 'poser')
            ->addGithubAsHostingServiceProvider()
            ->addReleasedVersion('9.0.1-RC')
            ->addReleasedVersion('10.0.1-beta')
            ->build();

        $result = $this->application->createStableVersionBadge('badges/poser');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_STABLE, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_STABLE, $result));
        self::assertTrue(self::badgeImageHasVersion('10.0.1', $result));
        self::assertTrue(self::badgeImageHasNotVersion('9.0.1', $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveAn404HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'package')
            ->addGithubAsHostingServiceProvider()
            ->addReleasedVersion('10.0.1')
            ->addHttpStatusCode(404)
            ->build();

        $result = $this->application->createStableVersionBadge('notexist/package');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveAn500HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'package')
            ->addGithubAsHostingServiceProvider()
            ->addReleasedVersion('10.0.1')
            ->addHttpStatusCode(500)
            ->build();

        $result = $this->application->createStableVersionBadge('notexist/package');

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

    public static function badgeImageHasVersion(string $subject, Image $image): bool
    {
        self::assertStringContainsString(
            $subject,
            $image->getContent(),
            \sprintf('Image content error:  %s version string not found in badge image.', $subject)
        );

        return true;
    }

    public static function badgeImageHasNotVersion(string $subject, Image $image): bool
    {
        self::assertStringNotContainsString(
            $subject,
            $image->getContent(),
            \sprintf('Image content error:  %s version string found in badge image.', $subject)
        );

        return true;
    }
}
