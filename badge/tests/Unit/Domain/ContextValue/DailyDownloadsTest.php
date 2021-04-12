<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\Common\PostFixCount;
use Badge\Application\Domain\Model\ContextValue\DailyDownloads;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DailyDownloadsTest extends TestCase
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'downloads';

    /**
     * @test
     */
    public function canBeCreated(): void
    {
        $inputValue = 10;

        $sut = new DailyDownloads($inputValue);

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(BadgeContext::class, $sut);
        self::assertInstanceOf(PostFixCount::class, $sut);
        self::assertInstanceOf(DailyDownloads::class, $sut);
    }

    /**
     * @test
     */
    public function negativeNumbersShouldThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        $inputValue = -10;

        new DailyDownloads($inputValue);
    }

    /**
     * @test
     */
    public function shouldReturnValueAsBadgeContext(): void
    {
        $inputValue = 10;

        $sut = new DailyDownloads($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('10 today', $sut->asBadgeValue());
    }

    /**
     * @test
     */
    public function shouldReturnTheRenderingProperties(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT,
            'subject-value' => '10 today',
            'color' => self::COLOR,
        ];

        $inputValue = 10;

        $sut = new DailyDownloads($inputValue);

        self::assertEquals($expectedRenderingProperties, $sut->renderingProperties());
    }

    /**
     * @test
     */
    public function zeroValueIsNormalizedAsOne(): void
    {
        $inputValue = 0;

        $sut = new DailyDownloads($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('1 today', $sut->asBadgeValue());
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
        $result = (new DailyDownloads($input))->asBadgeValue();

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
            [0,             '1 today'],
            [1,             '1 today'],
            [16,            '16 today'],
            [199,           '199 today'],
            [1012,          '1.01 k today'],
            [1212,          '1.21 k today'],
            [1999,          '2 k today'],
            [1003000,       '1 M today'],
            [9001003000,    '9 G today'],
            [9001003000000, '9 T today'],
        ];
    }
}
