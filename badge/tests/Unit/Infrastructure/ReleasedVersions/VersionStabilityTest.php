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
    }

    /**
     * @test
     * @dataProvider versionAndStabilityDataProvider
     */
    public function shouldDetectTheStabilty(string $version, string $expectedStability): void
    {
        $stability = VersionStability::fromString($version)->detect();

        self::assertEquals($expectedStability, $stability);
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function versionAndStabilityDataProvider(): array
    {
        return [
            ['1.0.0', 'Stable'],
            ['1.1.0', 'Stable'],
            ['2.0.0', 'Stable'],
            ['3.0.x-dev', 'Unstable'],
            ['v3.0.0-RC1', 'Unstable'],
            ['2.3.x-dev', 'Unstable'],
            ['2.2.x-dev', 'Unstable'],
            ['dev-master', 'Unstable'],
            ['2.1.x-dev', 'Unstable'],
            ['2.0.x-dev', 'Unstable'],
            ['v2.3.0-rc2', 'Unstable'],
            ['v2.3.0-RC1', 'Unstable'],
            ['v2.3.0-BETA2', 'Unstable'],
            ['v2.1.10', 'Stable'],
            ['v2.2.1', 'Stable'],
            ['0.1.0-alpha1', 'Unstable'],
            ['0.1.0-alpha', 'Unstable'],
        ];
    }
}
