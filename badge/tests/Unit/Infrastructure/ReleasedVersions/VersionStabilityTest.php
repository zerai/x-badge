<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Infrastructure\ReleasedVersions;

use Badge\Infrastructure\VersionStability;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Infrastructure\VersionStability */
final class VersionStabilityTest extends TestCase
{
    /**
     * @test
     */
    public function emptyVersionInConstructorShouldThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        VersionStability::fromString('');
    }

    /**
     * @test
     */
    public function shouldDetectTheStabilty(string $version = '1.5.0', string $expectedStability = 'stable'): void
    {
        $stability = VersionStability::fromString($version)->detect();

        self::assertEquals($expectedStability, $stability);
    }
}
