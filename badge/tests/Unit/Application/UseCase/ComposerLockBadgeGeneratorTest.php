<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Application\UseCase;

use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\Usecase\ComposerLockBadgeGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/** @covers \Badge\Application\Usecase\ComposerLockBadgeGenerator */
final class ComposerLockBadgeGeneratorTest extends TestCase
{
    /**
     * @var ContextProducer & MockObject
     */
    private $contextProducer;

    /**
     * @var ImageFactory & MockObject
     */
    private $imageFactory;

    private ComposerLockBadgeGenerator $useCase;

    public function setUp(): void
    {
        $this->contextProducer = $this->getMockBuilder(ContextProducer::class)
            ->onlyMethods(['contextFor'])
            ->getMock();

        $this->imageFactory = $this->getMockBuilder(ImageFactory::class)
            ->getMock();

        $this->useCase = new ComposerLockBadgeGenerator($this->contextProducer, $this->imageFactory);
    }

    /**
     * @test
     */
    public function canGenerateAComposerLockBadgeFromPackageName(): void
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

        $result = $this->useCase->createComposerLockBadge($packageName);

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

        $result = $this->useCase->createComposerLockBadge('irrelevant/irrelevant');

        self::assertInstanceOf(Image::class, $result);
    }
}
