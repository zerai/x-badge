<?php declare(strict_types=1);

namespace Badge\Tests\Unit;

use Badge\Application\Domain\Model\ValueObject\Color;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ColorTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidValueDataProvider
     */
    public function cantBeCreadetWithInvalidValue(string $inputValue): void
    {
        self::expectException(InvalidArgumentException::class);

        new Color($inputValue);
    }

    /**
     * @psalm-return Generator<string, array{0: string}, mixed, void>
     */
    public function invalidValueDataProvider(): Generator
    {
        yield 'empty value' => [''];

        //yield 'short value' => ['a'];

        //yield 'long value' => [\str_repeat('a', 100)];
    }

    /**
     * @test
     * @dataProvider rawValueDataProvider
     */
    public function inputValuesSouldBeNormalized(string $inputValue, string $expectedNormalizedVerson): void
    {
        $sut = new Color($inputValue);

        self::assertEquals($expectedNormalizedVerson, $sut->value());
    }

    /**
     * @psalm-return Generator<string, array{0: string, 1: string}, mixed, void>
     */
    public function rawValueDataProvider(): Generator
    {
        yield 'space before' => ['   000000', '000000'];

        yield 'space after' => ['000000   ', '000000'];
    }
}
