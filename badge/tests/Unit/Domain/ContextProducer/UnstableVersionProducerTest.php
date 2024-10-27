<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\Service\ContextProducer\UnstableVersionProducer;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingUnstableVersion;
use Badge\Tests\PHPUnitExtension\BadgeContextAssertionsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Core\Domain\Model\Service\ContextProducer\UnstableVersionProducer */
final class UnstableVersionProducerTest extends TestCase
{
    use BadgeContextAssertionsTrait;

    /**
     * @var ForReadingUnstableVersion & MockObject
     */
    private $contextReader;

    private UnstableVersionProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(ForReadingUnstableVersion::class)->getMock();

        $this->producer = new UnstableVersionProducer(
            $this->contextReader
        );
    }

    /**
     * @test
     */
    public function shouldProduceABadgeContext(): void
    {
        $expectedVersion = '0.0.1-alpha';

        $this->contextReader
            ->expects($this->once())
            ->method('readUnstableVersion')
            ->willReturn($expectedVersion);

        $result = $this->producer->contextFor('irrelevant/irrelevant');

        self::assertInstanceOf(BadgeContext::class, $result);
    }
}
