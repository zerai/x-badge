<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Application\BadgeImage;
use Badge\Application\Domain\Model\BadgeContext\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\ImageFactory;
use Badge\Application\Usecase\ComposerLockBadgeGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ComposerLockBadgeGeneratorTest extends TestCase
{
    /**
     * @var ImageFactory|MockObject
     */
    private $imageFactory;

    /**
     * @var ContextProducer|MockObject
     */
    private $contextProducer;

    private ComposerLockBadgeGenerator $useCase;

    public function setUp(): void
    {
        $this->imageFactory = $this->getMockBuilder(ImageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

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
        $aBadgeContext = BadgeContext::fromContextValue(ComposerLockFile::createAsCommitted());

        $this->contextProducer->expects($this->once())
            ->method('contextFor')
            //->with([$aPackageName])
            ->willReturn($aBadgeContext);

        $this->imageFactory->expects($this->once())
            ->method('createImageFromContext');

        $result = $this->useCase->getComposerLockBadge($aPackageName);

        self::assertInstanceOf(BadgeImage::class, $result);
    }
}
