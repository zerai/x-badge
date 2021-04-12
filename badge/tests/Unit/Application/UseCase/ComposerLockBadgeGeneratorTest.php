<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Application\UseCase;

use Badge\Application\Domain\Model\BadgeContext\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\Usecase\ComposerLockBadgeGenerator;
use Badge\Infrastructure\PoserImageFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use PUGX\Poser\Render\SvgPlasticRender;
use RuntimeException;

final class ComposerLockBadgeGeneratorTest extends TestCase
{
    /**
     * @var ImageFactory
     */
    private $imageFactory;

    /**
     * @var ContextProducer & MockObject
     */
    private $contextProducer;

    private ComposerLockBadgeGenerator $useCase;

    public function setUp(): void
    {
        // $this->imageFactory = $this->getMockBuilder(ImageFactory::class)
        //     ->disableOriginalConstructor()
        //     ->getMock();

        $this->imageFactory = $this->setUpImageFactory();

        $this->contextProducer = $this->getMockBuilder(ContextProducer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->useCase = new ComposerLockBadgeGenerator($this->contextProducer, $this->imageFactory);
    }

    /**
     * @test
     */
    public function canGenerateABadgeImage(string $aPackageName = 'foo/bar'): void
    {
        //$aBadgeContext = BadgeContext::fromContextValue(ComposerLockFile::createAsCommitted());
        $aBadgeContext = ComposerLockFile::createAsCommitted();

        $this->contextProducer->expects($this->once())
            ->method('contextFor')
            //->with([$aPackageName])
            ->willReturn($aBadgeContext);

        // $this->imageFactory->expects($this->once())
        //     ->method('createImageFromContext');

        $result = $this->useCase->createComposerLockBadge($aPackageName);

        self::assertInstanceOf(Image::class, $result);
        self::assertEquals('lock-committed-e60073.svg', $result->getFileName());
    }

    /**
     * @test
     */
    public function canGenerateADefaultBadgeImageOnError(string $aPackageName = 'foo/bar'): void
    {
        //$aBadgeContext = BadgeContext::fromContextValue(ComposerLockFile::createAsCommitted());
        $aBadgeContext = ComposerLockFile::createAsCommitted();

        $this->contextProducer->expects($this->once())
            ->method('contextFor')
            //->with([$aPackageName])
            ->will($this->throwException(new RuntimeException('...')));

        // $this->imageFactory->expects($this->once())
        //     ->method('createImageForDefaultBadge');

        $result = $this->useCase->createComposerLockBadge($aPackageName);

        self::assertInstanceOf(Image::class, $result);
        self::assertEquals('default-badge.svg', $result->getFileName());
    }

    private function setUpImageFactory(): PoserImageFactory
    {
        $poserGenerator = new Poser([
            new SvgFlatRender(),
            new SvgFlatSquareRender(),
            new SvgPlasticRender(),
        ]);

        return new PoserImageFactory($poserGenerator);
    }
}
