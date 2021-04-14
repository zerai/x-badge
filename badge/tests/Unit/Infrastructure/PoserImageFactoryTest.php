<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Infrastructure;

use Generator;
use PUGX\Poser\Poser;
use Badge\Application\Image;
use PHPUnit\Framework\TestCase;
use Badge\Application\BadgeImage;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgPlasticRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use Badge\Infrastructure\PoserImageFactory;
use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\DailyDownloads;
use Badge\Application\Domain\Model\ContextValue\TotalDownloads;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use Badge\Application\Domain\Model\ContextValue\MonthlyDownloads;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;

/** @covers \Badge\Infrastructure\PoserImageFactory */
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
     * @dataProvider renderableDownloadDataProvider
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

    /**
     * @psalm-return Generator<string,  array{0: BadgeContext, 1: string}>
     */
    public function renderableDownloadDataProvider(): Generator
    {
        yield 'a total download' => [new TotalDownloads(10), 'downloads-10-007ec6.svg'];
        // TODO rimuovere il . nel file name
        yield 'a total download whit normalized counter ' => [new TotalDownloads(100588), 'downloads-100.59-k-007ec6.svg'];
        yield 'a monthly download' => [new MonthlyDownloads(10), 'downloads-10-this-month-007ec6.svg'];
        // TODO rimuovere il . nel file name
        yield 'a monthly download with normalized counter' => [new MonthlyDownloads(1032555), 'downloads-1.03-M-this-month-007ec6.svg'];
        yield 'a daily download' => [new DailyDownloads(10), 'downloads-10-today-007ec6.svg'];
        // TODO rimuovere il . nel file name
        yield 'a daily download with normalized counter' => [new DailyDownloads(320860055), 'downloads-320.86-M-today-007ec6.svg'];
    }
}
