<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Application\BadgeImage;
use Badge\Application\Image;

final class DependentsUseCaseTest extends AcceptanceTestCase
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'dependents';

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
        $result = $this->application->createDependentsBadge('badges/poser');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR, $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeIfError(): void
    {
        $result = $this->application->createSuggestersBadge('notexist/package');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createABadgeForAPackageWithZeroDependents(): void
    {
        $result = $this->application->createSuggestersBadge('irrelevantvendor/package-with-zero-dependents');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
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
