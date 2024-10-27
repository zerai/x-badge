<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Application\UseCase;

use Badge\Core\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Core\Image;
use Badge\Core\ImageFactory;
use Badge\Core\Usecase\SuggestersBadgeGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/** @covers \Badge\Core\Usecase\SuggestersBadgeGenerator */
final class SuggestersBadgeGeneratorTest extends TestCase
{
    /**
     * @var ContextProducer & MockObject
     */
    private $contextProducer;

    /**
     * @var ImageFactory & MockObject
     */
    private $imageFactory;

    private SuggestersBadgeGenerator $useCase;

    protected function setUp(): void
    {
        $this->contextProducer = $this->getMockBuilder(ContextProducer::class)
            ->onlyMethods(['contextFor'])
            ->getMock();

        $this->imageFactory = $this->getMockBuilder(ImageFactory::class)
            ->getMock();

        $this->useCase = new SuggestersBadgeGenerator($this->contextProducer, $this->imageFactory);
    }

    /**
     * @test
     */
    public function canGenerateASuggestersBadgeFromPackageName(): void
    {
        $packageName = 'irrelevant/irrelevant';

        $this->contextProducer
            ->expects($this->once())
            ->method('contextFor')
            ->with($packageName);

        $this->imageFactory
            ->expects($this->never())
            ->method('createImageForDefaultBadge');

        $this->imageFactory
            ->expects($this->once())
            ->method('createImageFromContext');

        $result = $this->useCase->createSuggestersBadge($packageName);

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

        $result = $this->useCase->createSuggestersBadge('irrelevant/irrelevant');

        self::assertInstanceOf(Image::class, $result);
    }
}
