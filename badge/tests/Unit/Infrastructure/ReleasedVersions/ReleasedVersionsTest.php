<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Infrastructure;

use Badge\Infrastructure\ReleasedVersions;
use Generator;
use Packagist\Api\Result\Package\Version;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Infrastructure\ReleasedVersions */
final class ReleasedVersionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider lastStableVersionDataProvider
     * @param array<Version[]> $versions
     */
    public function canDetectLastStableVersion(array $versions, string $expectedVersion): void
    {
        $sut = ReleasedVersions::fromApiData($versions);

        self::assertEquals($expectedVersion, $sut->stableVersion());
    }

    /**
     * @psalm-return Generator<string, array{0: array<array-key, array<array-key, Version>>, 1: string}, mixed, void>
     */
    public function lastStableVersionDataProvider(): Generator
    {
        yield 'badges/poser versions' => [$this->loadFixturesForPackage('badges/poser'), 'v2.0.4'];
        yield 'doctrine/collections versions' => [$this->loadFixturesForPackage('doctrine/collections'), '1.6.7'];
        yield 'symfony/contracts versions' => [$this->loadFixturesForPackage('symfony/contracts'), 'v2.3.1'];
    }

    /**
     * @test
     * @dataProvider lastUnstableVersionDataProvider
     * @param array<Version[]> $versions
     */
    public function canDetectLastUnstableVersion(array $versions, string $expectedVersion): void
    {
        $sut = ReleasedVersions::fromApiData($versions);

        self::assertEquals($expectedVersion, $sut->unstableVersion());
    }

    /**
     * @psalm-return Generator<string, array{0: array<array-key, array<array-key, Version>>, 1: string}, mixed, void>
     */
    public function lastUnstableVersionDataProvider(): Generator
    {
        yield 'badges/poser versions' => [$this->loadFixturesForPackage('badges/poser'), '1.4.x-dev'];
        yield 'doctrine/collections versions' => [$this->loadFixturesForPackage('doctrine/collections'), '2.0.x-dev'];
        yield 'symfony/contracts versions' => [$this->loadFixturesForPackage('symfony/contracts'), 'v1.0.0-RC1'];
        yield 'ramsey/uuid versions' => [$this->loadFixturesForPackage('ramsey/uuid'), '4.0.0-beta2'];
    }

    /**
     * @test
     * @dataProvider licenseDataProvider
     * @param array<Version[]> $versions
     */
    public function canDetectLicense(array $versions, string $expectedLicense): void
    {
        $sut = ReleasedVersions::fromApiData($versions);

        self::assertEquals($expectedLicense, $sut->license());
    }

    /**
     * @psalm-return Generator<string, array{0: array<array-key, array<array-key, Version>>, 1: string}, mixed, void>
     */
    public function licenseDataProvider(): Generator
    {
        yield 'badges/poser versions' => [$this->loadFixturesForPackage('badges/poser'), 'MIT'];
        yield 'doctrine/collections versions' => [$this->loadFixturesForPackage('doctrine/collections'), 'MIT'];
        yield 'symfony/contracts versions' => [$this->loadFixturesForPackage('symfony/contracts'), 'MIT'];
    }

    /**
     * @return array<array<Version>>
     */
    private function loadFixturesForPackage(string $packageName): array
    {
        $string_data = '';
        switch ($packageName) {
            case 'badges/poser':
                $string_data = (string) \file_get_contents(
                    \dirname(__DIR__) . '/ReleasedVersions/serialized-versions-for-package-badges-poser.txt'
                ); break;
            case 'doctrine/collections':
                $string_data = (string) \file_get_contents(
                    \dirname(__DIR__) . '/ReleasedVersions/serialized-versions-for-package-doctrine-collections.txt'
                );
                break;
            case 'symfony/contracts':
                $string_data = (string) \file_get_contents(
                    \dirname(__DIR__) . '/ReleasedVersions/serialized-versions-for-package-symfony-contracts.txt'
                );
                break;
            case 'ramsey/uuid':
                $string_data = (string) \file_get_contents(
                    \dirname(__DIR__) . '/ReleasedVersions/serialized-versions-for-package-ramsey-uuid.txt'
                );
                break;

        }
        /** @psalm-suppress all */
        return \unserialize($string_data);
    }
}
