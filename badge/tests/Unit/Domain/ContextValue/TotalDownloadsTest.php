<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\Common\BaseCount;
use Badge\Application\Domain\Model\ContextValue\Suggesters;
use Badge\Application\Domain\Model\ContextValue\TotalDownloads;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

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

        $sut = new TotalDownloads($inputValue);

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

        new TotalDownloads($inputValue);
    }

    /**
     * @test
     */
    public function shouldReturnValueAsBadgeContext(): void
    {
        $inputValue = 10;
        $sut = new TotalDownloads($inputValue);

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
        $sut = new TotalDownloads($inputValue);

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
        $sut = new Suggesters($inputValue);

        self::assertIsString($sut->asBadgeValue());
        self::assertEquals('1', $sut->asBadgeValue());
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
        $sut = new Suggesters($input);

        $this->assertEquals($output, $sut->asBadgeValue());
    }

    /**
     * @return array<int, array<int, int|string>>
     *
     * @psalm-return array{0: array{0: int, 1: string}}
     */
    public static function getGoodNumberToConvert(): array
    {
        return [
            [0,             '1'],
            [1,             '1'],
            [16,            '16'],
            [199,           '199'],
            [1012,          '1.01 k'],
            [1212,          '1.21 k'],
            [1999,          '2 k'],
            [1003000,       '1 M'],
            [9001003000,    '9 G'],
            [9001003000000, '9 T'],
        ];
    }
}
