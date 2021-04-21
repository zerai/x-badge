<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Adapter\Out\PackagistContextValueReader;
use Badge\Infrastructure\Env;
use Badge\Tests\Integration\ApiMockServer\ApiMockServer;
use Generator;
use Packagist\Api\Client as packagistClient;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @group io-network
 * @covers \Badge\Adapter\Out\PackagistContextValueReader
 */
final class PackagistContextValueReaderReadFromPackagistApiServerTest extends TestCase
{
    private PackagistContextValueReader $reader;

    public static function setUpBeforeClass(): void
    {
        self::loadFixtureToApiMockServer();
    }

    public static function tearDownAfterClass(): void
    {
        ApiMockServer::reset();
    }

    protected function setUp(): void
    {
        $client = new packagistClient();
        $client->setPackagistUrl(Env::get('API_MOCK_SERVER'));
        $this->reader = new PackagistContextValueReader($client);
    }

    /**
     * @test
     * @dataProvider packageNameDataprovider
     */
    public function shouldReadTheSuggestersValueFromApiServer(string $packageName): void
    {
        $suggesters = $this->reader->readSuggesters($packageName);

        self::assertGreaterThanOrEqual(0, $suggesters);
    }

    /**
     * @test
     * @dataProvider packageNameDataprovider
     */
    public function shouldReadTheDependentsValueFromApiServer(string $packageName): void
    {
        $dependents = $this->reader->readDependents($packageName);

        self::assertGreaterThan(0, $dependents);
    }

    /**
     * @test
     * @dataProvider packageNameDataprovider
     */
    public function shouldReadTheDailyDownloadsValueFromApiServer(string $packageName): void
    {
        $dailyDownloads = $this->reader->readDailyDownloads($packageName);

        self::assertGreaterThan(0, $dailyDownloads);
    }

    /**
     * @test
     * @dataProvider packageNameDataprovider
     */
    public function shouldReadTheMonthlyDownloadsValueFromApiServer(string $packageName): void
    {
        $monthlyDownloads = $this->reader->readMonthlyDownloads($packageName);

        self::assertGreaterThan(0, $monthlyDownloads);
    }

    /**
     * @test
     * @dataProvider packageNameDataprovider
     */
    public function shouldReadTheTotalDownloadsValueFromApiServer(string $packageName): void
    {
        $totalDownloads = $this->reader->readTotalDownloads($packageName);

        self::assertGreaterThan(0, $totalDownloads);
    }

    /**
     * @test
     * @dataProvider packageNameDataprovider
     */
    public function shouldReadTheStableVersionValueFromApiServer(string $packageName): void
    {
        $stableVersion = $this->reader->readStableVersion($packageName);

        self::assertNotEmpty($stableVersion);
        self::assertStringNotContainsStringIgnoringCase($stableVersion, 'dev');
        self::assertStringNotContainsStringIgnoringCase($stableVersion, 'alpha');
        self::assertStringNotContainsStringIgnoringCase($stableVersion, 'beta');
        self::assertStringNotContainsStringIgnoringCase($stableVersion, 'rc');
    }

    /**
     * @test
     * @dataProvider packageNameDataprovider
     */
    public function shouldReadTheUnstableVersionValueFromApiServer(string $packageName): void
    {
        $unstableVersion = $this->reader->readUnstableVersion($packageName);

        self::assertNotEmpty($unstableVersion);
    }

    /**
     * @return Generator<string, array<int, string>>
     */
    public function packageNameDataprovider(): Generator
    {
        yield 'package badges/poser' => ['badges/poser'];
        yield 'package doctrine/collections' => ['doctrine/collections'];
        yield 'package monolog/monolog' => ['monolog/monolog'];
    }

    private static function loadFixtureToApiMockServer(): void
    {
        ApiMockServer::loadPackagistFixtureForPackage(
            '/packages/badges/poser.json',
            self::getFixtureContent(__DIR__ . '/Fixture/Packagist/package-badges-poser.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/packages/doctrine/collections.json',
            self::getFixtureContent(__DIR__ . '/Fixture/Packagist/package-doctrine-collections.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/packages/monolog/monolog.json',
            self::getFixtureContent(__DIR__ . '/Fixture/Packagist/package-monolog-monolog.json')
        );
    }

    private static function getFixtureContent(string $fixtureFile): string
    {
        if (! \file_exists($fixtureFile)) {
            throw new RuntimeException('Fixture file not found.');
        }

        /** @phpstan-ignore-next-line */
        return \file_get_contents($fixtureFile);
    }
}
