<?php declare(strict_types=1);

namespace Badge\Tests\Unit\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\TotalDownloadsProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\TotalDownloadsReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class TotalDownloadsProducerTest extends TestCase
{
    /**
     * @var TotalDownloadsReader & MockObject
     */
    private $contextReader;

    private TotalDownloadsProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(TotalDownloadsReader::class)->getMock();

        $this->producer = new TotalDownloadsProducer(
            $this->contextReader
        );
    }

    /**
     * @test
     */
    public function shouldProduceABadgeContext(): void
    {
        $expectedDependents = 10;

        $this->contextReader
            ->expects($this->once())
            ->method('readTotalDownloads')
            ->willReturn($expectedDependents);

        $result = $this->producer->contextFor('irrelevant/irrelevant');

        self::assertInstanceOf(BadgeContext::class, $result);
    }

    /**
     * @test
     */
    public function shouldRethrowException(): void
    {
        self::expectException(RuntimeException::class);

        $this->contextReader
            ->expects($this->once())
            ->method('readTotalDownloads')
            ->will($this->throwException(new RuntimeException('An exception')));

        $result = $this->producer->contextFor('irrelevant/irrelevant');
    }
}
