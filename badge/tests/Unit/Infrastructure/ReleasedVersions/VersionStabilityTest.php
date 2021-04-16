<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Infrastructure\ReleasedVersions;

use Badge\Infrastructure\VersionStability;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Infrastructure\VersionStability */
final class VersionStabilityTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidVersionStringDataProvider
     */
    public function emptyVersionInConstructorShouldThrowException(string $version): void
    {
        self::expectException(InvalidArgumentException::class);

        VersionStability::fromString($version);
    }

    /**
     * @psalm-return Generator<string, array{0: string}>
     */
    public function invalidVersionStringDataProvider(): Generator
    {
        yield 'empty version' => [''];
        yield 'too short version' => ['0.1'];
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
