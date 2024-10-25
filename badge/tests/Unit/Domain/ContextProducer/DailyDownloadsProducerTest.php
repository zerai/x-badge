<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\DailyDownloadsProducer;

use Badge\Application\Port\Driven\ForReadingDailyDownloads;
use Badge\Tests\PHPUnitExtension\BadgeContextAssertionsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\Service\ContextProducer\DailyDownloadsProducer */
final class DailyDownloadsProducerTest extends TestCase
{
    use BadgeContextAssertionsTrait;

    /**
     * @var ForReadingDailyDownloads & MockObject
     */
    private $contextReader;

    private DailyDownloadsProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(ForReadingDailyDownloads::class)->getMock();

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
        self::assertIsDailyDownloadsBadgeContext($result->renderingProperties());
    }
}
