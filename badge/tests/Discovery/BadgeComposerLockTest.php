<?php declare(strict_types=1);

namespace Badge\Tests\Discovery;

require_once(__DIR__ . '/Discovered.php');

use Badge\Application\ {
    BadgeComposerLockUsecase,
    BadgeUsecaseInterface
};

use Badge\Application\Domain\Model\ {
    ImageBadgeInterface
};

use PHPUnit\Framework\TestCase;

final class BadgeComposerLockTest extends TestCase
{
    /**
     * @test
     */
    public function canBeCreated(): void
    {
        self::assertInstanceOf(BadgeUsecaseInterface::class, new \Badge\Application\BadgeComposerLockUsecase());
    }

    /**
     * @test
     */
    public function canCreateABadgeImage(): void
    {
        $sut = new \Badge\Application\BadgeComposerLockUsecase();
        self::assertInstanceOf(ImageBadgeInterface::class, $sut->createBadge());
    }
}
