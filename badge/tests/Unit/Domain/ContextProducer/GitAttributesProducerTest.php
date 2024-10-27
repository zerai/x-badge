<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextProducer;

use Badge\AdapterForObtainingBadgeContextValuesForCommittedFile\CommittedFileChecker;
use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\RepositoryDetail;
use Badge\Core\Domain\Model\Service\ContextProducer\CommittedFileDetector;
use Badge\Core\Domain\Model\Service\ContextProducer\GitAttributesProducer;
use Badge\Core\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\ForDetectingRepositoryBranch;
use Badge\Core\Port\Driven\ForReadingRepositoryDetail\ForReadingRepositoryDetail;
use Badge\Tests\PHPUnitExtension\BadgeContextAssertionsTrait;
use Generator;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/** @covers \Badge\Core\Domain\Model\Service\ContextProducer\GitAttributesProducer */
final class GitAttributesProducerTest extends TestCase
{
    use BadgeContextAssertionsTrait;

    private const GITHUB_REPOSITORY_PREFIX = 'blob';

    private const BITBUCKET_REPOSITORY_PREFIX = 'src';

    private const STATUS_COMMITTED = 200;

    private const STATUS_UNCOMMITTED = 404;

    private const STATUS_ERROR = 500;

    /**
     * @var ClientInterface & MockObject
     */
    private $httpClient;

    /**
     * @var ForDetectingRepositoryBranch & MockObject
     */
    private $defaultBranchDetector;

    /**
     * @var ForReadingRepositoryDetail & MockObject
     */
    private $repositoryReader;

    private GitAttributesProducer $badgeContextProducer;

    protected function setUp(): void
    {
        $this->httpClient = $this->getMockBuilder(ClientInterface::class)
            ->getMock();

        $this->defaultBranchDetector = $this->getMockBuilder(ForDetectingRepositoryBranch::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repositoryReader = $this->getMockBuilder(ForReadingRepositoryDetail::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fileChecker = new CommittedFileChecker($this->httpClient, $this->defaultBranchDetector);
        $detector = new CommittedFileDetector($this->repositoryReader, $fileChecker);

        $this->badgeContextProducer = new GitAttributesProducer($detector);
    }

    /**
     * @test
     * @dataProvider gitattributesFileDataProvider
     * @covers \Badge\Core\Domain\Model\Service\ContextProducer\CommittedFileDetector
     */
    public function shouldProduceAGitattributesBadgeContextForAPackageHostedOnGitHub(int $httpFileStatus, string $assertionToCall): void
    {
        $aBranchName = 'mybranch';
        $aPackageUrl = 'https://github.com/irrelevantvendor/irrelevantpackagename';

        $expectedTargetCommittedFileUrl = sprintf('%s/%s/%s/%s', $aPackageUrl, self::GITHUB_REPOSITORY_PREFIX, $aBranchName, '.gitattributes');
        $aFakeRepositoryDetail = RepositoryDetail::fromRepositoryUrl($aPackageUrl);

        $this->repositoryReader
            ->expects($this->once())
            ->method('readRepositoryDetail')
            ->willReturn($aFakeRepositoryDetail);

        $this->defaultBranchDetector
            ->expects($this->once())
            ->method('getDefaultBranch')
            ->with($aFakeRepositoryDetail)
            ->willReturn($aBranchName);

        $response = $this->createMockWithoutInvokingTheOriginalConstructor(
            ResponseInterface::class,
            ['getStatusCode', 'withStatus', 'getReasonPhrase', 'getProtocolVersion', 'withProtocolVersion', 'getHeaders', 'hasHeader', 'getHeader', 'getHeaderLine', 'withHeader', 'withAddedHeader', 'withoutHeader', 'getBody', 'withBody']
        );
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn($httpFileStatus);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('HEAD', $expectedTargetCommittedFileUrl)
            ->willReturn($response);

        $result = $this->badgeContextProducer->contextFor('irrelevant/irrelevant');

        self::assertInstanceOf(BadgeContext::class, $result);
        self::{$assertionToCall}($result->renderingProperties());
    }

    /**
     * @test
     * @dataProvider gitattributesFileDataProvider
     */
    public function shouldProduceAGitattributesBadgeContextForAPackageHostedOnBitbucket(int $httpFileStatus, string $assertionToCall): void
    {
        $aBranchName = 'mybranch';
        $aPackageUrl = 'https://bitbucket.org/irrelevantvendor/irrelevantpackagename';

        $expectedTargetCommittedFileUrl = sprintf('%s/%s/%s/%s', $aPackageUrl, self::BITBUCKET_REPOSITORY_PREFIX, $aBranchName, '.gitattributes');
        $aFakeRepositoryDetail = RepositoryDetail::fromRepositoryUrl($aPackageUrl);

        $this->repositoryReader
            ->expects($this->once())
            ->method('readRepositoryDetail')
            ->willReturn($aFakeRepositoryDetail);

        $this->defaultBranchDetector
            ->expects($this->once())
            ->method('getDefaultBranch')
            ->with($aFakeRepositoryDetail)
            ->willReturn($aBranchName);

        $response = $this->createMockWithoutInvokingTheOriginalConstructor(
            ResponseInterface::class,
            ['getStatusCode', 'withStatus', 'getReasonPhrase', 'getProtocolVersion', 'withProtocolVersion', 'getHeaders', 'hasHeader', 'getHeader', 'getHeaderLine', 'withHeader', 'withAddedHeader', 'withoutHeader', 'getBody', 'withBody']
        );
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn($httpFileStatus);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('HEAD', $expectedTargetCommittedFileUrl)
            ->willReturn($response);

        $result = $this->badgeContextProducer->contextFor('irrelevant/irrelevant');

        self::assertInstanceOf(BadgeContext::class, $result);
        self::{$assertionToCall}($result->renderingProperties());
    }

    /**
     * @psalm-return Generator<string, array{0: int, 1: string}>
     */
    public function gitattributesFileDataProvider(): Generator
    {
        yield 'committed .gitattributes' =>
            [
                self::STATUS_COMMITTED,
                'assertIsCommittedGitAttributesBadgeContext',
            ];

        yield 'uncommitted .gitattributes' =>
            [
                self::STATUS_UNCOMMITTED,
                'assertIsUncommittedGitAttributesBadgeContext',
            ];

        yield 'error .gitattributes' =>
            [
                self::STATUS_ERROR,
                'assertIsErrorGitAttributesFileBadgeContext',
            ];
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
