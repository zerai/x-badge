<?php declare(strict_types=1);

namespace Badge\Tests\Unit\UseCase;

use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\Usecase\MonthlyDownloadsBadgeGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/** @covers \Badge\Application\Usecase\MonthlyDownloadsBadgeGenerator */
final class MonthlyDownloadsBadgeGeneratorTest extends TestCase
{
    /**
     * @var ContextProducer & MockObject
     */
    private $contextProducer;

    /**
     * @var ImageFactory & MockObject
     */
    private $imageFactory;

    private MonthlyDownloadsBadgeGenerator $useCase;

    protected function setUp(): void
    {
        $this->contextProducer = $this->getMockBuilder(ContextProducer::class)
            ->onlyMethods(['contextFor'])
            ->getMock();

        $this->imageFactory = $this->getMockBuilder(ImageFactory::class)
            ->getMock();

        $this->useCase = new MonthlyDownloadsBadgeGenerator($this->contextProducer, $this->imageFactory);
    }

    /**
     * @test
     */
    public function canGenerateASuggestersBadgeFromPackageName(): void
    {
        $this->contextProducer
            ->expects($this->once())
            ->method('contextFor');

        $this->imageFactory
            ->expects($this->never())
            ->method('createImageForDefaultBadge');

        $result = $this->useCase->createMonthlyDownloadsBadge('irrelevant/irrelevant');

        self::assertInstanceOf(Image::class, $result);
    }

    /**
     * @test
     */
    public function canGenerateADefaultBadgeIfError(): void
    {
        $this->contextProducer
            ->expects($this->once())
            ->method('contextFor')
            ->will($this->throwException(new RuntimeException('Network Error.')));

        $this->imageFactory
            ->expects($this->once())
            ->method('createImageForDefaultBadge');

        $result = $this->useCase->createMonthlyDownloadsBadge('irrelevant/irrelevant');

        self::assertInstanceOf(Image::class, $result);
    }
}
