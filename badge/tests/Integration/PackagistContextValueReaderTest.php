<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Adapter\Out\PackagistContextValueReader;
use Packagist\Api\Client;
use Packagist\Api\Result\Package;
use Packagist\Api\Result\Package\Downloads;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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
}
