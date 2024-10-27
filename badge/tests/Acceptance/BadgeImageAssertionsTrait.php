<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Core\Image;

trait BadgeImageAssertionsTrait
{
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
            sprintf('Badge image error: color %s not found in badge image.', $color)
        );

        return true;
    }

    public static function badgeImageHasSubject(string $subject, Image $image): bool
    {
        self::assertStringContainsString(
            $subject,
            $image->getContent(),
            sprintf('Badge image error: subject %s not found in badge image.', $subject)
        );

        return true;
    }

    public static function badgeImageHasSubjectValue(string $subjectValue, Image $image): bool
    {
        self::assertStringContainsString(
            $subjectValue,
            $image->getContent(),
            sprintf('Badge image error: subject-value %s not found in badge image.', $subjectValue)
        );

        return true;
    }

    public static function badgeImageHasVersion(string $subject, Image $image): bool
    {
        self::assertStringContainsString(
            $subject,
            $image->getContent(),
            sprintf('Badge image error: %s version string not found in badge image.', $subject)
        );

        return true;
    }

    public static function badgeImageHasNotVersion(string $subject, Image $image): bool
    {
        self::assertStringNotContainsString(
            $subject,
            $image->getContent(),
            sprintf('Badge image error: %s version string found in badge image.', $subject)
        );

        return true;
    }
}
