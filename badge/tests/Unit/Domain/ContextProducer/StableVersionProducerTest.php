<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\Service\ContextProducer\StableVersionProducer;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingStableVersion;
use Badge\Tests\PHPUnitExtension\BadgeContextAssertionsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Core\Domain\Model\Service\ContextProducer\StableVersionProducer */
final class StableVersionProducerTest extends TestCase
{
    use BadgeContextAssertionsTrait;

    /**
     * @var \Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingStableVersion & MockObject
     */
    private $contextReader;

    private StableVersionProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(ForReadingStableVersion::class)->getMock();

        $this->producer = new StableVersionProducer(
            $this->contextReader
        );
    }

    /**
     * @test
     */
    public function shouldProduceABadgeContext(): void
    {
        $expectedVersion = '0.0.1';

        $this->contextReader
            ->expects($this->once())
            ->method('readStableVersion')
            ->willReturn($expectedVersion);

        $result = $this->producer->contextFor('irrelevant/irrelevant');

        self::assertInstanceOf(BadgeContext::class, $result);
        self::assertIsStableVersionBadgeContext($result->renderingProperties());
    }
}
