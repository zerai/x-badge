<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Infrastructure\ServiceContainer;

use Badge\Application\BadgeApplicationInterface;
use Badge\Infrastructure\ProductionServiceContainer;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Infrastructure\ProductionServiceContainer */
final class ProdServiceContainerTest extends TestCase
{
    /**
     * @test
     */
    public function canInstantiateAProductionApplication(): void
    {
        $container = new ProductionServiceContainer();

        self::assertInstanceOf(BadgeApplicationInterface::class, $container->application());
    }
}
