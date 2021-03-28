<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Adapter\Out\CommittedFileChecker;
use Badge\Adapter\Out\CommittedFileDetector;
use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\RepositoryDetail;
use Badge\Application\Domain\Model\Service\ContextProducer\ComposerLockProducer;
use Badge\Application\Domain\Model\Service\RepositoryReader\RepositoryDetailReader;
use Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ComposerLockProducerTest extends TestCase
{
    private const STATUS_COMMITTED = 200;

    private const STATUS_UNCOMMITTED = 404;

    private const STATUS_ERROR = 500;

    /**
     * @var CommittedFileChecker|MockObject
     */
    private $fileChecker;

    /**
     * @var RepositoryDetailReader|MockObject
     */
    private $repositoryReader;

    /**
     * @var CommittedFileDetector
     */
    private $composerLockDetector;

    /**
     * @var ComposerLockProducer
     */
    private $badgeContextProducer;

    protected function setUp(): void
    {
        $this->fileChecker = $this->getMockBuilder(CommittedFileChecker::class)
            ->disableOriginalConstructor()
            ->setMethods(['checkFile'])
            ->getMock();

        $this->repositoryReader = $this->getMockBuilder(RepositoryDetailReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->composerLockDetector = new CommittedFileDetector($this->repositoryReader, $this->fileChecker);

        $this->badgeContextProducer = new ComposerLockProducer($this->composerLockDetector);
    }

    /**
     * @test
     * @dataProvider composerFileDataProvider
     * @param array<mixed> $expectedArray
     */
    public function shouldProduceBadgeContextForACommittedComposerFile(int $httpFileStatus, array $expectedArray): void
    {
        $this->fileChecker->expects($this->once())
            ->method('checkFile')
            ->willReturn($httpFileStatus);

        $aFakeRepositoryDetail = RepositoryDetail::fromRepositoryUrl('https://github.com/foo/bar');

        $this->repositoryReader
            ->expects($this->once())
            ->method('readRepositoryDetail')
            ->willReturn($aFakeRepositoryDetail);

        $result = $this->badgeContextProducer->contextFor('phpunit/phpunit');

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
}
