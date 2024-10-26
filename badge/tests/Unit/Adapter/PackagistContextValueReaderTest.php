<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Adapter;

use Badge\AdapterForReadingBadgeContextValues\PackagistContextValueReader;
use Packagist\Api\Client;
use Packagist\Api\Result\Package;
use Packagist\Api\Result\Package\Downloads;
use Packagist\Api\Result\Package\Version;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\AdapterForReadingBadgeContextValues\PackagistContextValueReader */
final class PackagistContextValueReaderTest extends TestCase
{
    /**
     * @var Client & MockObject
     */
    private $packagistClient;

    private PackagistContextValueReader $packagistContextValueReader;

    protected function setUp(): void
    {
        $this->packagistClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $this->packagistContextValueReader = new PackagistContextValueReader($this->packagistClient);
    }

    /**
     * @test
     */
    public function shouldReadASuggestersValue(): void
    {
        $expectedSuggesters = 5;
        $package = $this->getMockBuilder(Package::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSuggesters'])
            ->getMock();

        $package->expects($this->once())
            ->method('getSuggesters')
            ->willReturn($expectedSuggesters);

        $this->packagistClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($package);

        $result = $this->packagistContextValueReader->readSuggesters('irrelevant/irrelevant');

        self::assertEquals($expectedSuggesters, $result);
    }

    /**
     * @test
     */
    public function shouldReadADependentsValue(): void
    {
        $expectedDependents = 5;
        $package = $this->getMockBuilder(Package::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDependents'])
            ->getMock();

        $package->expects($this->once())
            ->method('getDependents')
            ->willReturn($expectedDependents);

        $this->packagistClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($package);

        $result = $this->packagistContextValueReader->readDependents('irrelevant/irrelevant');

        self::assertEquals($expectedDependents, $result);
    }

    /**
     * @test
     */
    public function shouldReadATotalDownloadsValue(): void
    {
        $expectedTotalDownloads = 5;

        $downloads = $this->getMockBuilder(Downloads::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTotal'])
            ->getMock();
        $downloads->expects($this->once())
            ->method('getTotal')
            ->willReturn($expectedTotalDownloads);

        $package = $this->getMockBuilder(Package::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDownloads'])
            ->getMock();

        $package->expects($this->once())
            ->method('getDownloads')
            ->willReturn($downloads);

        $this->packagistClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($package);

        $result = $this->packagistContextValueReader->readTotalDownloads('irrelevant/irrelevant');

        self::assertEquals($expectedTotalDownloads, $result);
    }

    /**
     * @test
     */
    public function shouldReadAMonthlyDownloadsValue(): void
    {
        $expectedTotalDownloads = 5;

        $downloads = $this->getMockBuilder(Downloads::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMonthly'])
            ->getMock();
        $downloads->expects($this->once())
            ->method('getMonthly')
            ->willReturn($expectedTotalDownloads);

        $package = $this->getMockBuilder(Package::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDownloads'])
            ->getMock();

        $package->expects($this->once())
            ->method('getDownloads')
            ->willReturn($downloads);

        $this->packagistClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($package);

        $result = $this->packagistContextValueReader->readMonthlyDownloads('irrelevant/irrelevant');

        self::assertEquals($expectedTotalDownloads, $result);
    }

    /**
     * @test
     */
    public function shouldReadADailyDownloadsValue(): void
    {
        $expectedDailyDownloads = 5;

        $downloads = $this->getMockBuilder(Downloads::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDaily'])
            ->getMock();
        $downloads->expects($this->once())
            ->method('getDaily')
            ->willReturn($expectedDailyDownloads);

        $package = $this->getMockBuilder(Package::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDownloads'])
            ->getMock();

        $package->expects($this->once())
            ->method('getDownloads')
            ->willReturn($downloads);

        $this->packagistClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($package);

        $result = $this->packagistContextValueReader->readDailyDownloads('irrelevant/irrelevant');

        self::assertEquals($expectedDailyDownloads, $result);
    }

    /**
     * @test
     */
    public function shouldReadAStableVersionValue(): void
    {
        $expectedStableVersion = 'v0.1.0';

        $anUnstableVersionString = 'v0.1.0-beta';

        $aStableVersion = $this->getMockBuilder(Version::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVersion', 'getVersionNormalized'])
            ->getMock();
        $aStableVersion->expects($this->once())
            ->method('getVersion')
            ->willReturn($expectedStableVersion);
        $aStableVersion->expects($this->once())
            ->method('getVersionNormalized')
            ->willReturn($expectedStableVersion);

        $anUnstableVersion = $this->getMockBuilder(Version::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVersion', 'getVersionNormalized'])
            ->getMock();
        $anUnstableVersion->expects($this->once())
            ->method('getVersion')
            ->willReturn($anUnstableVersionString);
        $anUnstableVersion->expects($this->once())
            ->method('getVersionNormalized')
            ->willReturn($anUnstableVersionString);

        $package = $this->getMockBuilder(Package::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVersions'])
            ->getMock();

        $package->expects($this->once())
            ->method('getVersions')
            ->willReturn([$aStableVersion, $anUnstableVersion]);

        $this->packagistClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($package);

        $result = $this->packagistContextValueReader->readStableVersion('irrelevant/irrelevant');

        self::assertEquals($expectedStableVersion, $result);
    }

    /**
     * @test
     */
    public function shouldReadAnUnstableVersionValue(): void
    {
        $expectedUnstableVersion = 'v0.1.0-beta';

        $aStableVersionString = 'v0.1.0';

        $anUnstableVersion = $this->getMockBuilder(Version::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVersion', 'getVersionNormalized'])
            ->getMock();
        $anUnstableVersion->expects($this->once())
            ->method('getVersion')
            ->willReturn($expectedUnstableVersion);
        $anUnstableVersion->expects($this->once())
            ->method('getVersionNormalized')
            ->willReturn($expectedUnstableVersion);

        $aStableVersion = $this->getMockBuilder(Version::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVersion', 'getVersionNormalized'])
            ->getMock();
        $aStableVersion->expects($this->once())
            ->method('getVersion')
            ->willReturn($aStableVersionString);
        $aStableVersion->expects($this->once())
            ->method('getVersionNormalized')
            ->willReturn($aStableVersionString);

        $package = $this->getMockBuilder(Package::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVersions'])
            ->getMock();

        $package->expects($this->once())
            ->method('getVersions')
            ->willReturn([$aStableVersion, $anUnstableVersion]);

        $this->packagistClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($package);

        $result = $this->packagistContextValueReader->readUnstableVersion('irrelevant/irrelevant');

        self::assertEquals($expectedUnstableVersion, $result);
    }
}
