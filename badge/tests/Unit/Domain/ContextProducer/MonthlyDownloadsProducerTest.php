<?php declare(strict_types=1);

namespace Badge\Tests\Unit\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\MonthlyDownloadsProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\MonthlyDownloadsReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\Service\ContextProducer\MonthlyDownloadsProducer */
final class MonthlyDownloadsProducerTest extends TestCase
{
    /**
     * @var MonthlyDownloadsReader & MockObject
     */
    private $contextReader;

    private MonthlyDownloadsProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(MonthlyDownloadsReader::class)->getMock();

        $this->producer = new MonthlyDownloadsProducer(
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
            ->method('readMonthlyDownloads')
            ->willReturn($expectedDependents);

        $result = $this->producer->contextFor('irrelevant/irrelevant');

        self::assertInstanceOf(BadgeContext::class, $result);
    }
}
