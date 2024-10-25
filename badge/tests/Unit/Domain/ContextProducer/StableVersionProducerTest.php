<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\StableVersionProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingStableVersion;
use Badge\Tests\PHPUnitExtension\BadgeContextAssertionsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\Service\ContextProducer\StableVersionProducer */
final class StableVersionProducerTest extends TestCase
{
    use BadgeContextAssertionsTrait;

    /**
     * @var ForReadingStableVersion & MockObject
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
