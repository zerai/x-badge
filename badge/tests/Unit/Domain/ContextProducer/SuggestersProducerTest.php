<?php declare(strict_types=1);

namespace Badge\Tests\Unit\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\Service\ContextProducer\SuggestersProducer;
use Badge\Application\Domain\Model\Service\ContextProducer\SuggestersReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class SuggestersProducerTest extends TestCase
{
    /**
     * @var SuggestersReader & MockObject
     */
    private $contextReader;

    private SuggestersProducer $producer;

    protected function setUp(): void
    {
        $this->contextReader = $this->getMockBuilder(SuggestersReader::class)->getMock();

        $this->producer = new SuggestersProducer(
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
            ->method('readSuggesters')
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
            ->method('readSuggesters')
            ->will($this->throwException(new RuntimeException('An exception')));

        $result = $this->producer->contextFor('irrelevant/irrelevant');
    }
}
