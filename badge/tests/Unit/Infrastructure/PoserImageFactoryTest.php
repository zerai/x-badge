<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Infrastructure;

use Badge\Application\BadgeImage;
use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;
use Badge\Application\Image;
use Badge\Infrastructure\PoserImageFactory;
use Generator;
use PHPUnit\Framework\TestCase;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use PUGX\Poser\Render\SvgPlasticRender;

final class PoserImageFactoryTest extends TestCase
{
    private PoserImageFactory $imageFactory;

    public function setUp(): void
    {
        $poserGenerator = new Poser([
            new SvgFlatRender(),
            new SvgFlatSquareRender(),
            new SvgPlasticRender(),
        ]);

        $this->imageFactory = new PoserImageFactory($poserGenerator);
    }

    /**
     * @test
     */
    public function canCreateImageForDefaultBadge(): void
    {
        $result = $this->imageFactory->createImageForDefaultBadge();

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertEquals('default-badge.svg', $result->getFileName());
    }

    /**
     * @test
     * @dataProvider renderableCommittedFileDataProvider
     * @param string $expectedFileName
     */
    public function canCreateImageFromContext(BadgeContext $badgeContext, $expectedFileName): void
    {
        $result = $this->imageFactory->createImageFromContext($badgeContext);

        self::assertInstanceOf(Image::class, $result);
        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertEquals($expectedFileName, $result->getFileName());
    }

    /**
     * @psalm-return Generator<string,  array{0: BadgeContext, 1: string}>
     */
    public function renderableCommittedFileDataProvider(): Generator
    {
        yield '.lock committed' => [ComposerLockFile::createAsCommitted(), 'lock-committed-e60073.svg'];
        yield '.lock uncommitted' => [ComposerLockFile::createAsUncommitted(), 'lock-uncommitted-99004d.svg'];
        yield '.lock error' => [ComposerLockFile::createAsError(), 'Error-checking-aa0000.svg'];
        yield '.attributes committed' => [GitAttributesFile::createAsCommitted(), 'gitattributes-committed-96d490.svg'];
        yield '.attributes uncommitted' => [GitAttributesFile::createAsUnCommitted(), 'gitattributes-uncommitted-ad6c4b.svg'];
        yield '.attributes error' => [GitAttributesFile::createAsError(), 'Error-checking-aa0000.svg'];
    }
}
