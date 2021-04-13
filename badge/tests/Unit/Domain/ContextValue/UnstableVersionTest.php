<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\UnstableVersion;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\ContextValue\UnstableVersion */
final class UnstableVersionTest extends TestCase
{
    private const COLOR_UNSTABLE = 'e68718';

    private const SUBJECT_UNSTABLE = 'unstable';

    /**
     * @test
     */
    public function canBeCreated(): void
    {
        $sut = UnstableVersion::fromString('v0.0.1');

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(BadgeContext::class, $sut);
    }

    /**
     * @test
     * @dataProvider invalidValueInConstructorDataProvider
     */
    public function invalidInputValueShouldThrowException(string $input): void
    {
        self::expectException(InvalidArgumentException::class);

        UnstableVersion::fromString($input);
    }

    /**
     * @psalm-return Generator<string, array{0: string}, mixed, void>
     */
    public function invalidValueInConstructorDataProvider(): Generator
    {
        yield 'empty value' => [''];
        yield 'too short value' => ['v0.1'];
    }

    /**
     * @test
     * @dataProvider unformattedValueInConstructorDataProvider
     */
    public function unformattedInputValueShouldBeNormaized(string $input, string $expectedOutput): void
    {
        $sut = UnstableVersion::fromString($input);

        self::assertEquals($expectedOutput, $sut->asBadgeValue());
    }

    /**
     * @psalm-return Generator<string, array{0: string, 1: string}, mixed, mixed>
     */
    public function unformattedValueInConstructorDataProvider(): Generator
    {
        yield 'space before' => ['    v0.0.1', 'v0.0.1'];
        yield 'space after' => ['v0.0.1    ', 'v0.0.1'];
        yield 'capitalize ' => ['V0.0.1-BETA    ', 'v0.0.1-beta'];
        yield 'capitalize and mixed' => ['V0.0.1-BeTa    ', 'v0.0.1-beta'];
    }

    /**
     * @test
     */
    public function shouldReturnRenderingProperties(): void
    {
        $input = 'v0.0.1';

        $expectedRenderingProperties = [
            'subject' => self::SUBJECT_UNSTABLE,
            'subject-value' => $input,
            'color' => self::COLOR_UNSTABLE,
        ];

        $data = UnstableVersion::fromString($input)->renderingProperties();

        self::assertArrayHasKey('subject', $data);
        self::assertEquals($expectedRenderingProperties['subject'], $data['subject']);
        self::assertArrayHasKey('subject-value', $data);
        self::assertEquals($expectedRenderingProperties['subject-value'], $data['subject-value']);
        self::assertArrayHasKey('color', $data);
        self::assertEquals($expectedRenderingProperties['color'], $data['color']);
    }
}
