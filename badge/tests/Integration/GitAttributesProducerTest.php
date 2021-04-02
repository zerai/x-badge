<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Adapter\Out\CommittedFileChecker;
use Badge\Adapter\Out\CommittedFileDetector;
use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\RepositoryDetail;
use Badge\Application\Domain\Model\Service\ContextProducer\GitAttributesProducer;
use Badge\Application\Domain\Model\Service\RepositoryReader\RepositoryDetailReader;
use Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GitAttributesProducerTest extends TestCase
{
    private const STATUS_COMMITTED = 200;

    private const STATUS_UNCOMMITTED = 404;

    private const STATUS_ERROR = 500;

    /**
     * @var CommittedFileChecker & MockObject
     */
    private $fileChecker;

    /**
     * @var RepositoryDetailReader & MockObject
     */
    private $repositoryReader;

    /**
     * @var GitAttributesProducer
     */
    private $badgeContextProducer;

    protected function setUp(): void
    {
        $this->fileChecker = $this->getMockBuilder(CommittedFileChecker::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['checkFile'])
            ->getMock();

        $this->repositoryReader = $this->getMockBuilder(RepositoryDetailReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $gitAttributesFileDetector = new CommittedFileDetector($this->repositoryReader, $this->fileChecker);

        $this->badgeContextProducer = new GitAttributesProducer($gitAttributesFileDetector);
    }

    /**
     * @test
     * @dataProvider gitattributesFileDataProvider
     * @param array<mixed> $expectedArray
     */
    public function shouldProduceBadgeContextForAGitattributesFile(int $httpFileStatus, array $expectedArray): void
    {
        $this->fileChecker
            ->expects($this->once())
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
    public function gitattributesFileDataProvider(): Generator
    {
        yield 'committed .gitattributes' =>
            [
                self::STATUS_COMMITTED,
                [
                    'subject' => '.gitattributes',
                    'subject-value' => 'committed',
                    'color' => '#96d490',
                ],
            ];
        yield 'uncommitted .gitattributes' =>
        [
            self::STATUS_UNCOMMITTED,
            [
                'subject' => '.gitattributes',
                'subject-value' => 'uncommitted',
                'color' => '#ad6c4b',
            ],
        ];
        yield 'error .gitattributes' =>
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
