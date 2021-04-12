<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\Common\PostFixCount;
use Badge\Application\Domain\Model\ContextValue\MonthlyDownloads;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

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

        $sut = new MonthlyDownloads($inputValue);

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(BadgeContext::class, $sut);
        self::assertInstanceOf(PostFixCount::class, $sut);
        self::assertInstanceOf(MonthlyDownloads::class, $sut);
    }

    /**
     * @test
     */
    public function negativeNumbersShouldThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        $inputValue = -10;

        new MonthlyDownloads($inputValue);
    }

    /**
     * @test
     */
    public function shouldReturnValueAsBadgeContext(): void
    {
        $inputValue = 10;

        $sut = new MonthlyDownloads($inputValue);

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

        $sut = new MonthlyDownloads($inputValue);

        self::assertEquals($expectedRenderingProperties, $sut->renderingProperties());
    }

    /**
     * @test
     */
    public function zeroValueIsNormalizedAsOne(): void
    {
        $inputValue = 0;

        $sut = new MonthlyDownloads($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('1 this month', $sut->asBadgeValue());
    }

    /**
     * stessi valori di conversione usati nel test pugx/badge-poser
     *
     * @link https://github.com/PUGX/badge-poser/blob/23f5ce009c4367006f3a6d61f12d331c918c32f3/tests/Badge/Service/TextNormalizerTest.php#L58
     */

    /**
     * @dataProvider getGoodNumberToConvert
     */
    public function testGoodNumberToTextConversion(int $input, string $output): void
    {
        $result = (new MonthlyDownloads($input))->asBadgeValue();

        $this->assertEquals($output, $result);
    }

    /**
     * @return (int|string)[][]
     *
     * @psalm-return array{0: array{0: int, 1: string}}
     */
    public static function getGoodNumberToConvert(): array
    {
        return [
            [0,             '1 this month'],
            [1,             '1 this month'],
            [16,            '16 this month'],
            [199,           '199 this month'],
            [1012,          '1.01 k this month'],
            [1212,          '1.21 k this month'],
            [1999,          '2 k this month'],
            [1003000,       '1 M this month'],
            [9001003000,    '9 G this month'],
            [9001003000000, '9 T this month'],
        ];
    }
}
