<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Application\BadgeImage;
use Badge\Application\Image;
use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Application\Usecase\UnstableVersionBadgeGenerator */
final class UnstableVersionUseCaseTest extends AcceptanceTestCase
{
    private const COLOR_UNSTABLE = 'e68718';

    private const SUBJECT_UNSTABLE = 'unstable';

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
    public function createAnUnstableVersionABadgeForAPackage(): void
    {
        PackagistBuilder::withVendorAndProjectName('badges', 'poser')
            ->addGithubAsHostingServiceProvider()
            ->addReleasedVersion('9.0.1-beta')
            ->addReleasedVersion('10.0.1-dev')
            ->build();

        $result = $this->application->createUnstableVersionBadge('badges/poser');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_UNSTABLE, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_UNSTABLE, $result));
        self::assertTrue(self::badgeImageHasVersion('10.0.1-dev', $result));
        self::assertTrue(self::badgeImageHasNotVersion('9.0.1-beta', $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveAn404HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'package')
            ->addGithubAsHostingServiceProvider()
            ->addReleasedVersion('1.0.0-RC')
            ->addHttpStatusCode(404)
            ->build();

        $result = $this->application->createUnstableVersionBadge('notexist/package');

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
            ->addReleasedVersion('1.0.0-RC')
            ->addHttpStatusCode(500)
            ->build();

        $result = $this->application->createUnstableVersionBadge('notexist/package');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createADefaultBadgeWhenThereIsNoAnUnstaleRelease(): void
    {
        self::markTestIncomplete('Missing Requirements: Non è chiaro quale dovrebbe essere il comportamento.');
        PackagistBuilder::withVendorAndProjectName('notexist', 'package')
            ->addGithubAsHostingServiceProvider()
            ->addReleasedVersion('1.0.0')
            ->addHttpStatusCode(500)
            ->build();

        $result = $this->application->createUnstableVersionBadge('irrelevantvendor/with-no-unstable-version');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_UNSTABLE, $result));
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
