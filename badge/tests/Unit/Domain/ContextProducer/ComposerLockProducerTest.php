<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextProducer;

use Badge\Adapter\Out\CommittedFileChecker;
use Badge\Adapter\Out\CommittedFileDetector;
use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\RepositoryDetail;
use Badge\Application\Domain\Model\Service\ContextProducer\ComposerLockProducer;
use Badge\Application\Domain\Model\Service\DefaultBranchDetector\DetectableBranch;
use Badge\Application\Domain\Model\Service\RepositoryReader\RepositoryDetailReader;
use Generator;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/** @covers \Badge\Application\Domain\Model\Service\ContextProducer\ComposerLockProducer */
final class ComposerLockProducerTest extends TestCase
{
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
     * @var DetectableBranch & MockObject
     */
    private $defaultBranchDetector;

    /**
     * @var RepositoryDetailReader & MockObject
     */
    private $repositoryReader;

    /**
     * @var ComposerLockProducer
     */
    private $badgeContextProducer;

    protected function setUp(): void
    {
        $this->httpClient = $this->getMockBuilder(ClientInterface::class)
            ->getMock();

        $this->defaultBranchDetector = $this->getMockBuilder(DetectableBranch::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repositoryReader = $this->getMockBuilder(RepositoryDetailReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fileChecker = new CommittedFileChecker($this->httpClient, $this->defaultBranchDetector);
        $detector = new CommittedFileDetector($this->repositoryReader, $fileChecker);

        $this->badgeContextProducer = new ComposerLockProducer($detector);
    }

    /**
     * @test
     * @dataProvider composerFileDataProvider
     * @param array<mixed> $expectedArray
     */
    public function shouldProduceAComposerLockBadgeContextForAPackageHostedOnGitHub(int $httpFileStatus = 200, array $expectedArray = []): void
    {
        $aBranchName = 'mybranch';
        $aPackageUrl = 'https://github.com/irrelevantvendor/irrelevantpackagename';

        $expectedTargetCommittedFileUrl = \sprintf('%s/%s/%s/%s', $aPackageUrl, self::GITHUB_REPOSITORY_PREFIX, $aBranchName, 'composer.lock');
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

        $data = $result->renderingProperties();

        self::assertInstanceOf(BadgeContext::class, $result);
        self::assertArrayHasKey('subject', $data);
        self::assertEquals($expectedArray['subject'], $data['subject']);
        self::assertArrayHasKey('subject-value', $data);
        self::assertEquals($expectedArray['subject-value'], $data['subject-value']);
        self::assertArrayHasKey('color', $data);
        self::assertEquals($expectedArray['color'], $data['color']);
    }

    /**
     * @test
     * @dataProvider composerFileDataProvider
     * @param array<mixed> $expectedArray
     */
    public function shouldProduceAComposerLockBadgeContextForAPackageHostedOnBitbucket(int $httpFileStatus, array $expectedArray): void
    {
        $aBranchName = 'mybranch';
        $aPackageUrl = 'https://bitbucket.org/irrelevantvendor/irrelevantpackagename';

        $expectedTargetCommittedFileUrl = \sprintf('%s/%s/%s/%s', $aPackageUrl, self::BITBUCKET_REPOSITORY_PREFIX, $aBranchName, 'composer.lock');
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

        $data = $result->renderingProperties();

        self::assertInstanceOf(BadgeContext::class, $result);
        self::assertArrayHasKey('subject', $data);
        self::assertEquals($expectedArray['subject'], $data['subject']);
        self::assertArrayHasKey('subject-value', $data);
        self::assertEquals($expectedArray['subject-value'], $data['subject-value']);
        self::assertArrayHasKey('color', $data);
        self::assertEquals($expectedArray['color'], $data['color']);
    }

    /**
     * @psalm-return Generator<string, array{0: int, 1: array{subject: string, subject-value: string, color: string}}, mixed, void>
     */
    public function composerFileDataProvider(): Generator
    {
        yield 'committed composer.lock' =>
            [
                self::STATUS_COMMITTED,
                [
                    'subject' => '.lock',
                    'subject-value' => 'committed',
                    'color' => '#e60073',
                ],
            ];
        yield 'uncommitted composer.lock' =>
        [
            self::STATUS_UNCOMMITTED,
            [
                'subject' => '.lock',
                'subject-value' => 'uncommitted',
                'color' => '#99004d',
            ],
        ];
        yield 'error composer.lock' =>
        [
            self::STATUS_ERROR,
            [
                'subject' => 'Error',
                'subject-value' => 'checking',
                'color' => '#aa0000',
            ],
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
