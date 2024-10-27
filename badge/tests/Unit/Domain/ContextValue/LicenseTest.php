<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextualizableValue;
use Badge\Core\Domain\Model\ContextValue\License;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Badge\Core\Domain\Model\ContextValue\License
 */
class LicenseTest extends TestCase
{
    private const COLOR = '428F7E';

    private const SUBJECT = 'license';

    private const TEXT_NO_LICENSE = 'no';

    /**
     * @test
     */
    public function canBeCreated(): void
    {
        $sut = License::fromString('v0.0.1');

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(BadgeContext::class, $sut);
    }

    /**
     * @test
     */
    public function canBeCreatedAsNoLicense(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT,
            'subject-value' => self::TEXT_NO_LICENSE,
            'color' => self::COLOR,
        ];

        $data = License::withNoLicense()->renderingProperties();

        self::assertArrayHasKey('subject', $data);
        self::assertEquals($expectedRenderingProperties['subject'], $data['subject']);
        self::assertArrayHasKey('subject-value', $data);
        self::assertEquals($expectedRenderingProperties['subject-value'], $data['subject-value']);
        self::assertArrayHasKey('color', $data);
        self::assertEquals($expectedRenderingProperties['color'], $data['color']);
    }

    /**
     * @test
     */
    public function shouldReturnRenderingProperties(): void
    {
        $input = 'MIT';

        $expectedRenderingProperties = [
            'subject' => self::SUBJECT,
            'subject-value' => $input,
            'color' => self::COLOR,
        ];

        $data = License::fromString($input)->renderingProperties();

        self::assertArrayHasKey('subject', $data);
        self::assertEquals($expectedRenderingProperties['subject'], $data['subject']);
        self::assertArrayHasKey('subject-value', $data);
        self::assertEquals($expectedRenderingProperties['subject-value'], $data['subject-value']);
        self::assertArrayHasKey('color', $data);
        self::assertEquals($expectedRenderingProperties['color'], $data['color']);
    }

    /**
     * @test
     * @dataProvider invalidValueInConstructorDataProvider
     */
    public function invalidInputValueShouldThrowException(string $input): void
    {
        self::expectException(InvalidArgumentException::class);

        License::fromString($input);
    }

    /**
     * @psalm-return Generator<string, array{0: string}, mixed, void>
     */
    public function invalidValueInConstructorDataProvider(): Generator
    {
        yield 'empty value' => [''];
        //yield 'too short value' => ['v0.1'];
    }

    /**
     * @test
     * @dataProvider unformattedValueInConstructorDataProvider
     */
    public function inputValueShouldBeNormaized(string $input, string $expectedOutput): void
    {
        $sut = License::fromString($input);

        self::assertEquals($expectedOutput, $sut->asBadgeValue());
    }

    /**
     * @psalm-return Generator<string, array{0: string, 1: string}, mixed, mixed>
     */
    public function unformattedValueInConstructorDataProvider(): Generator
    {
        yield 'space before' => ['    MIT', 'MIT'];
        yield 'space after' => ['GPL    ', 'GPL'];
    }
}
