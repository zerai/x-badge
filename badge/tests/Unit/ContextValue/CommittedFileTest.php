<?php declare(strict_types=1);

namespace Badge\Tests\Unit\ContextValue;

use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\Common\CommittedFile;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CommittedFileTest extends TestCase
{
    /**
     * @test
     */
    public function canBeCreated(): void
    {
        self::assertInstanceOf(ContextualizableValue::class, new CommittedFile('committed'));
    }

    /**
     * @test
     */
    public function canReturnValueAsBadgeContext(): void
    {
        $sut = new CommittedFile('uncommitted');

        self::assertEquals('uncommitted', $sut->asBadgeValue());
    }

    /**
     * @test
     * @dataProvider invalidValueInConstructorDataProvider
     */
    public function invalidInputValueShouldThrowException(string $input): void
    {
        self::expectException(InvalidArgumentException::class);

        new CommittedFile($input);
    }

    /**
     * @psalm-return Generator<string, array{0: string}, mixed, void>
     */
    public function invalidValueInConstructorDataProvider(): Generator
    {
        yield 'empty value' => [''];
        yield 'not allowed value' => ['foo'];
    }

    /**
     * @test
     * @dataProvider unformattedValueInConstructorDataProvider
     */
    public function unformattedInputValueShouldBeNormaized(string $input, string $expectedOutput): void
    {
        $sut = new CommittedFile($input);

        self::assertEquals($expectedOutput, $sut->value());
    }

    /**
     * @psalm-return Generator<string, array{0: string, 1: string}>
     */
    public function unformattedValueInConstructorDataProvider(): Generator
    {
        yield 'space before' => ['    committed', 'committed'];
        yield 'space after' => ['committed    ', 'committed'];
        yield 'capitalize ' => ['COMMITTED    ', 'committed'];
        yield 'capitalize and mixed' => ['CoMmItTeD    ', 'committed'];
    }
}
