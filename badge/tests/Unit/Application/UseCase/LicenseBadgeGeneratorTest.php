<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Application\UseCase;

use Badge\Core\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Core\Image;
use Badge\Core\ImageFactory;
use Badge\Core\Usecase\LicenseBadgeGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Badge\Core\Usecase\LicenseBadgeGenerator
 */
class LicenseBadgeGeneratorTest extends TestCase
{
    /**
     * @var ContextProducer & MockObject
     */
    private $contextProducer;

    /**
     * @var ImageFactory & MockObject
     */
    private $imageFactory;

    private LicenseBadgeGenerator $useCase;

    protected function setUp(): void
    {
        $this->contextProducer = $this->getMockBuilder(ContextProducer::class)
            ->onlyMethods(['contextFor'])
            ->getMock();

        $this->imageFactory = $this->getMockBuilder(ImageFactory::class)
            ->getMock();

        $this->useCase = new LicenseBadgeGenerator($this->contextProducer, $this->imageFactory);
    }

    /**
     * @test
     */
    public function canGenerateAnLicenseBadgeFromPackageName(): void
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

        $result = $this->useCase->createLicenseBadge($packageName);

        self::assertInstanceOf(Image::class, $result);
    }
}
