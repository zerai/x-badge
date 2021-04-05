<?php declare(strict_types=1);

namespace Badge\Tests\Unit\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\DependentsProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\DependentsReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class DependentsProducerTest extends TestCase
{
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
    }

    /**
     * @test
     */
    public function shouldRethrowException(): void
    {
        self::expectException(RuntimeException::class);

        $this->contextReader
            ->expects($this->once())
            ->method('readDependents')
            ->will($this->throwException(new RuntimeException('An exception')));

        $result = $this->producer->contextFor('irrelevant/irrelevant');
    }
}
