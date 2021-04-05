<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Adapter;

use Badge\Adapter\Out\CommittedFileChecker;
use Badge\Adapter\Out\DefaultBranchDetector;
use Badge\Application\Domain\Model\RepositoryDetail;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

final class CommittedFileCheckerTest extends TestCase
{
    private const STATUS_COMMITTED = 200;

    private const STATUS_UNCOMMITTED = 404;

    private const STATUS_ERROR = 500;

    private const GITHUB_REPOSITORY_URL = 'https://github.com/foo/bar';

    /**
     * @var ClientInterface & MockObject
     */
    private $httpClient;

    /**
     * @var DefaultBranchDetector & MockObject
     */
    private $branchDetector;

    private CommittedFileChecker $fileChecker;

    protected function setUp(): void
    {
        $this->httpClient = $this->getMockBuilder(ClientInterface::class)->getMock();
        $this->branchDetector = $this->createMock(DefaultBranchDetector::class);
        $this->fileChecker = new CommittedFileChecker($this->httpClient, $this->branchDetector);
    }

    /**
     * @test
     */
    public function shouldDetectACommittedFile(): void
    {
        $repositoryDetail = RepositoryDetail::fromRepositoryUrl(self::GITHUB_REPOSITORY_URL);

        $this->branchDetector
            ->expects($this->once())
            ->method('getDefaultBranch');

        $response = $this->createMockWithoutInvokingTheOriginalConstructor(
            ResponseInterface::class,
            ['getStatusCode', 'withStatus', 'getReasonPhrase', 'getProtocolVersion', 'withProtocolVersion', 'getHeaders', 'hasHeader', 'getHeader', 'getHeaderLine', 'withHeader', 'withAddedHeader', 'withoutHeader', 'getBody', 'withBody']
        );
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $result = $this->fileChecker->checkFile($repositoryDetail, 'irrelevant-filename.txt');

        self::assertEquals(self::STATUS_COMMITTED, $result);
    }

    /**
     * @test
     */
    public function shouldDetectAnUncommittedFile(): void
    {
        $repositoryDetail = RepositoryDetail::fromRepositoryUrl(self::GITHUB_REPOSITORY_URL);

        $this->branchDetector
            ->expects($this->once())
            ->method('getDefaultBranch');

        $response = $this->createMockWithoutInvokingTheOriginalConstructor(
            ResponseInterface::class,
            ['getStatusCode', 'withStatus', 'getReasonPhrase', 'getProtocolVersion', 'withProtocolVersion', 'getHeaders', 'hasHeader', 'getHeader', 'getHeaderLine', 'withHeader', 'withAddedHeader', 'withoutHeader', 'getBody', 'withBody']
        );
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(404);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $result = $this->fileChecker->checkFile($repositoryDetail, 'irrelevant-filename.txt');

        self::assertEquals(self::STATUS_UNCOMMITTED, $result);
    }

    /**
     * @test
     */
    public function notAvailableDefaultBranchShouldBeDetectAsError(): void
    {
        $repositoryDetail = RepositoryDetail::fromRepositoryUrl(self::GITHUB_REPOSITORY_URL);

        $this->branchDetector
            ->expects($this->once())
            ->method('getDefaultBranch')
            ->will($this->throwException(new RuntimeException()));

        $this->httpClient
            ->expects($this->never())
            ->method('request')
            ->willReturn($this->any());

        $result = $this->fileChecker->checkFile($repositoryDetail, 'irrelevant-filename.txt');

        self::assertEquals(self::STATUS_ERROR, $result);
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
