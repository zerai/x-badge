<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Adapter\Out\PackagistContextValueReader;
use Packagist\Api\Client;
use Packagist\Api\Result\Package;
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
}
