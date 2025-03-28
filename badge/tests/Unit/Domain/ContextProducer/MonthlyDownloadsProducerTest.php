<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\Service\ContextProducer\MonthlyDownloadsProducer;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingMonthlyDownloads;
use Badge\Tests\PHPUnitExtension\BadgeContextAssertionsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Core\Domain\Model\Service\ContextProducer\MonthlyDownloadsProducer */
final class MonthlyDownloadsProducerTest extends TestCase
{
    use BadgeContextAssertionsTrait;

    /**
     * @var ForReadingMonthlyDownloads & MockObject
     */
    private $contextReader;

    private MonthlyDownloadsProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(ForReadingMonthlyDownloads::class)->getMock();

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
        self::assertIsMonthlyDownloadsBadgeContext($result->renderingProperties());
    }
}
