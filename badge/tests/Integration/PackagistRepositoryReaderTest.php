<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Adapter\Out\PackagistRepositoryReader;
use Badge\Application\Domain\Model\RepositoryDetail;
use Packagist\Api\Client as PackagistClient;
use Packagist\Api\Result\Package;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class PackagistRepositoryReaderTest extends TestCase
{
    /**
     * @var PackagistClient & MockObject
     */
    private $packagistClient;

    /**
     * @var PackagistRepositoryReader
     */
    private $repositoryReader;

    protected function setUp(): void
    {
        $this->packagistClient = $this->getMockBuilder(PackagistClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $this->repositoryReader = new PackagistRepositoryReader($this->packagistClient);
    }

    /**
     * @test
     */
    public function shouldReturnARepositoryDetail(): void
    {
        $packageName = 'phpunit/phpunit';

        $package = $this->createMockWithoutInvokingTheOriginalConstructor(
            Package::class,
            ['getRepository']
        );
        $package->expects($this->once())
            ->method('getRepository')
            ->willReturn('https://github.com/user/repository');

        $this->packagistClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($package);

        $result = $this->repositoryReader->readRepositoryDetail($packageName);

        self::assertInstanceOf(RepositoryDetail::class, $result);
    }

    /**
     * @test
     */
    public function shouldReturnCustomExceptionForNetworkError(): void
    {
        self::markTestIncomplete('Not yet implemented.');
    }

    /**
     * @param array<string> $methods
     * @psalm-param class-string $className
     */
    private function createMockWithoutInvokingTheOriginalConstructor(string $className, array $methods = []): MockObject
    {
        return $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->onlyMethods($methods)
            ->getMock();
    }
}
