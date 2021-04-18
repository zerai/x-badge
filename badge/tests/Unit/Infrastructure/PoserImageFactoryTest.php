<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Infrastructure;

use Badge\Application\BadgeImage;
use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use Badge\Application\Domain\Model\ContextValue\DailyDownloads;
use Badge\Application\Domain\Model\ContextValue\Dependents;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;
use Badge\Application\Domain\Model\ContextValue\MonthlyDownloads;
use Badge\Application\Domain\Model\ContextValue\StableVersion;
use Badge\Application\Domain\Model\ContextValue\Suggesters;
use Badge\Application\Domain\Model\ContextValue\TotalDownloads;
use Badge\Application\Image;
use Badge\Infrastructure\PoserImageFactory;
use Generator;
use PHPUnit\Framework\TestCase;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use PUGX\Poser\Render\SvgPlasticRender;

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
     * @dataProvider renderableDependentsDataProvider
     * @dataProvider renderableSuggestersDataProvider
     * @dataProvider renderableVersionssDataProvider
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
        yield 'a total download' => [TotalDownloads::withCount(10), 'downloads-10-007ec6.svg'];
        yield 'a total download with normalized counter ' => [TotalDownloads::withCount(100588), 'downloads-100-59-k-007ec6.svg'];
        yield 'a monthly download' => [new MonthlyDownloads(10), 'downloads-10-this-month-007ec6.svg'];
        yield 'a monthly download with normalized counter' => [new MonthlyDownloads(1032555), 'downloads-1-03-M-this-month-007ec6.svg'];
        yield 'a daily download' => [DailyDownloads::withCount(10), 'downloads-10-today-007ec6.svg'];
        yield 'a daily download with normalized counter' => [DailyDownloads::withCount(320860055), 'downloads-320-86-M-today-007ec6.svg'];
    }

    /**
     * @psalm-return Generator<string,  array{0: BadgeContext, 1: string}>
     */
    public function renderableDependentsDataProvider(): Generator
    {
        yield 'a dependents' => [Dependents::withCount(10), 'dependents-10-007ec6.svg'];
        yield 'a dependents with normalized counter ' => [Dependents::withCount(100588), 'dependents-100-59-k-007ec6.svg'];
    }

    /**
     * @psalm-return Generator<string,  array{0: BadgeContext, 1: string}>
     */
    public function renderableSuggestersDataProvider(): Generator
    {
        yield 'a suggesters' => [Suggesters::withCount(10), 'suggesters-10-007ec6.svg'];
        yield 'a suggesters with normalized counter ' => [Suggesters::withCount(100588), 'suggesters-100-59-k-007ec6.svg'];
    }

    /**
     * @psalm-return Generator<string,  array{0: BadgeContext, 1: string}>
     */
    public function renderableVersionssDataProvider(): Generator
    {
        yield 'a stable version' => [StableVersion::fromString('1.0.5'), 'stable-1-0-5-28a3df.svg'];
        yield 'a stable version with no-release' => [StableVersion::withNoRelease(), 'stable-No-Release-28a3df.svg'];
        yield 'an unstable version' => [StableVersion::fromString('1.0.5-dev'), 'stable-1-0-5-dev-28a3df.svg'];
    }
}
