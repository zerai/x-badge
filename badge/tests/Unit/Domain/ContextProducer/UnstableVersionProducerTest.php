<?php declare(strict_types=1);

namespace Badge\Tests\Unit\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\UnstableVersionProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\UnstableVersionReader;
use Badge\Tests\PHPUnitExtension\BadgeContextAssertionsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\Service\ContextProducer\UnstableVersionProducer */
final class UnstableVersionProducerTest extends TestCase
{
    use BadgeContextAssertionsTrait;

    /**
     * @var UnstableVersionReader & MockObject
     */
    private $contextReader;

    private UnstableVersionProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(UnstableVersionReader::class)->getMock();

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
