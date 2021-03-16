<?php declare(strict_types=1);

namespace Badge\Tests\Unit\BadgeContext;

use Badge\Application\Domain\Model\BadgeContext\Text;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TextTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidValueDataProvider
     */
    public function cantBeCreadetWithInvalidValue(string $inputValue): void
    {
        self::expectException(InvalidArgumentException::class);

        new Text($inputValue);
    }

    /**
     * @psalm-return Generator<string, array{0: string}, mixed, void>
     */
    public function invalidValueDataProvider(): Generator
    {
        yield 'empty value' => [''];

        //yield 'short value' => ['a'];

        yield 'long value' => [\str_repeat('a', 100)];
    }

    /**
     * @test
     * @dataProvider rawValueDataProvider
     */
    public function inputValueShouldBeNormalized(string $inputValue, string $expectedNormalizedVerson): void
    {
        $sut = new Text($inputValue);

        self::assertEquals($expectedNormalizedVerson, $sut->value());
    }

    /**
     * @psalm-return Generator<string, array{0: string, 1: string}, mixed, void>
     */
    public function rawValueDataProvider(): Generator
    {
        yield 'space before' => ['   irrelevant', 'irrelevant'];

        yield 'space after' => ['irrelevant   ', 'irrelevant'];
    }
}
