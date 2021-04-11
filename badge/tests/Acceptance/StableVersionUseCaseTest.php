<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Application\BadgeImage;
use Badge\Application\Image;

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
        parent::tearDown();
    }

    /**
     * @test
     */
    public function createABadgeForAPackage(): void
    {
        $result = $this->application->createStableVersionBadge('badges/poser');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR_STABLE, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_STABLE, $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeIfError(): void
    {
        $result = $this->application->createStableVersionBadge('notexist/package');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createABadgeForAPackageWithZeroSuggesters(): void
    {
        $result = $this->application->createStableVersionBadge('irrelevantvendor/package-with-zero-suggesters');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT_STABLE, $result));
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
