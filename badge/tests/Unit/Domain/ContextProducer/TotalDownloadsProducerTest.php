<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\TotalDownloadsProducer;
use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingTotalDownloads;
use Badge\Tests\PHPUnitExtension\BadgeContextAssertionsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\Service\ContextProducer\TotalDownloadsProducer */
final class TotalDownloadsProducerTest extends TestCase
{
    use BadgeContextAssertionsTrait;

    /**
     * @var ForReadingTotalDownloads & MockObject
     */
    private $contextReader;

    private TotalDownloadsProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(ForReadingTotalDownloads::class)->getMock();

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
        self::assertIsTotalDownloadsBadgeContext($result->renderingProperties());
    }
}
