<?php declare(strict_types=1);

namespace Badge\Tests\Unit\UseCase;

use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\Usecase\DailyDownloadsBadgeGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class DailyDownloadsBadgeGeneratorTest extends TestCase
{
    /**
     * @var ContextProducer & MockObject
     */
    private $contextProducer;

    /**
     * @var ImageFactory & MockObject
     */
    private $imageFactory;

    private DailyDownloadsBadgeGenerator $useCase;

    protected function setUp(): void
    {
        $this->contextProducer = $this->getMockBuilder(ContextProducer::class)
            ->onlyMethods(['contextFor'])
            ->getMock();

        $this->imageFactory = $this->getMockBuilder(ImageFactory::class)
            ->getMock();

        $this->useCase = new DailyDownloadsBadgeGenerator($this->contextProducer, $this->imageFactory);
    }

    /**
     * @test
     */
    public function canGenerateADailyDownloadBadgeFromPackageName(): void
    {
        $this->contextProducer
            ->expects($this->once())
            ->method('contextFor');

        $this->imageFactory
            ->expects($this->never())
            ->method('createImageForDefaultBadge');

        $result = $this->useCase->createDailyDownloadsBadge('irrelevant/irrelevant');

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

        $result = $this->useCase->createDailyDownloadsBadge('irrelevant/irrelevant');

        self::assertInstanceOf(Image::class, $result);
    }
}
