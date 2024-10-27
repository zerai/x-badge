<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextualizableValue;
use Badge\Core\Domain\Model\ContextValue\Common\BaseCount;
use Badge\Core\Domain\Model\ContextValue\TotalDownloads;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Core\Domain\Model\ContextValue\TotalDownloads */
final class TotalDownloadsTest extends TestCase
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'downloads';

    /**
     * @test
     */
    public function canBeCreated(): void
    {
        $inputValue = 10;

        $sut = TotalDownloads::withCount($inputValue);

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(BadgeContext::class, $sut);
        self::assertInstanceOf(BaseCount::class, $sut);
        self::assertInstanceOf(TotalDownloads::class, $sut);
    }

    /**
     * @test
     */
    public function negativeNumbersShouldThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        $inputValue = -10;

        TotalDownloads::withCount($inputValue);
    }

    /**
     * @test
     */
    public function shouldReturnValueAsBadgeContext(): void
    {
        $inputValue = 10;
        $sut = TotalDownloads::withCount($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('10', $sut->asBadgeValue());
    }

    /**
     * @test
     */
    public function shouldReturnTheRenderingProperties(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT,
            'subject-value' => '10',
            'color' => self::COLOR,
        ];

        $inputValue = 10;
        $sut = TotalDownloads::withCount($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('10', $sut->asBadgeValue());

        self::assertEquals($expectedRenderingProperties, $sut->renderingProperties());
    }

    /**
     * @test
     */
    public function zeroValueIsNormalizedAsOne(): void
    {
        $inputValue = 0;
        $sut = TotalDownloads::withCount($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('1', $sut->asBadgeValue());
    }

    /**
     * @test
     */
    public function shouldApplyNumberNormalization(): void
    {
        $inputValue = 9001003000000;

        $sut = TotalDownloads::withCount($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('9 T', $sut->asBadgeValue());
    }
}
