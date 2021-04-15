<?php declare(strict_types=1);

namespace Badge\Tests\Unit\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\DependentsProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\DependentsReader;
use Badge\Tests\PHPUnitExtension\BadgeContextAssertionsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\Service\ContextProducer\DependentsProducer */
final class DependentsProducerTest extends TestCase
{
    use BadgeContextAssertionsTrait;

    /**
     * @var DependentsReader & MockObject
     */
    private $contextReader;

    private DependentsProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(DependentsReader::class)->getMock();

        $this->producer = new DependentsProducer(
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
            ->method('readDependents')
            ->willReturn($expectedDependents);

        $result = $this->producer->contextFor('irrelevant/irrelevant');

        self::assertInstanceOf(BadgeContext::class, $result);
        self::assertIsDependentsBadgeContext($result->renderingProperties());
    }
}
