<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\Common\BaseCount;
use Badge\Application\Domain\Model\ContextValue\MonthlyDownloads;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\ContextValue\MonthlyDownloads */
final class MonthlyDownloadsTest extends TestCase
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'downloads';

    /**
     * @test
     */
    public function canBeCreated(): void
    {
        $inputValue = 10;

        $sut = MonthlyDownloads::withCount($inputValue);

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(BadgeContext::class, $sut);
        self::assertInstanceOf(BaseCount::class, $sut);
        self::assertInstanceOf(MonthlyDownloads::class, $sut);
    }

    /**
     * @test
     */
    public function negativeNumbersShouldThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        $inputValue = -10;

        MonthlyDownloads::withCount($inputValue);
    }

    /**
     * @test
     */
    public function shouldReturnValueAsBadgeContext(): void
    {
        $inputValue = 10;

        $sut = MonthlyDownloads::withCount($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('10 this month', $sut->asBadgeValue());
    }

    /**
     * @test
     */
    public function shouldReturnTheRenderingProperties(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT,
            'subject-value' => '10 this month',
            'color' => self::COLOR,
        ];

        $inputValue = 10;

        $sut = MonthlyDownloads::withCount($inputValue);

        self::assertEquals($expectedRenderingProperties, $sut->renderingProperties());
    }

    /**
     * @test
     */
    public function zeroValueIsNormalizedAsOne(): void
    {
        $inputValue = 0;

        $sut = MonthlyDownloads::withCount($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('1 this month', $sut->asBadgeValue());
    }

    /**
     * @test
     */
    public function shouldApplyNumberNormalization(): void
    {
        $inputValue = 9001003000000;

        $sut = MonthlyDownloads::withCount($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('9 T this month', $sut->asBadgeValue());
    }
}
