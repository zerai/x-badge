<?php declare(strict_types=1);

namespace Badge\Tests\Unit\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\DailyDownloadsProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\DailyDownloadsReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class DailyDownloadsProducerTest extends TestCase
{
    /**
     * @var DailyDownloadsReader & MockObject
     */
    private $contextReader;

    private DailyDownloadsProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(DailyDownloadsReader::class)->getMock();

        $this->producer = new DailyDownloadsProducer(
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
            ->method('readDailyDownloads')
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
            ->method('readDailyDownloads')
            ->will($this->throwException(new RuntimeException('An exception')));

        $result = $this->producer->contextFor('irrelevant/irrelevant');
    }
}
