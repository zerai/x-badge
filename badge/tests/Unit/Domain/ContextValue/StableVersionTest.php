<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextualizableValue;
use Badge\Core\Domain\Model\ContextValue\StableVersion;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Core\Domain\Model\ContextValue\StableVersion */
final class StableVersionTest extends TestCase
{
    private const COLOR_STABLE = '28a3df';

    private const SUBJECT_STABLE = 'stable';

    private const TEXT_NO_STABLE_RELEASE = 'No Release';

    /**
     * @test
     */
    public function canBeCreated(): void
    {
        $sut = StableVersion::fromString('v0.0.1');

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(BadgeContext::class, $sut);
    }

    /**
     * @test
     */
    public function canBeCreatedWithNoRelease(): void
    {
        $sut = StableVersion::withNoRelease();

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

        StableVersion::fromString($input);
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
        $sut = StableVersion::fromString($input);

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
            'subject' => self::SUBJECT_STABLE,
            'subject-value' => $input,
            'color' => self::COLOR_STABLE,
        ];

        $data = StableVersion::fromString($input)->renderingProperties();

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
    public function noReleaseVersionShouldReturnRenderingProperties(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT_STABLE,
            'subject-value' => self::TEXT_NO_STABLE_RELEASE,
            'color' => self::COLOR_STABLE,
        ];

        $data = StableVersion::withNoRelease()->renderingProperties();

        self::assertArrayHasKey('subject', $data);
        self::assertEquals($expectedRenderingProperties['subject'], $data['subject']);
        self::assertArrayHasKey('subject-value', $data);
        self::assertEquals($expectedRenderingProperties['subject-value'], $data['subject-value']);
        self::assertArrayHasKey('color', $data);
        self::assertEquals($expectedRenderingProperties['color'], $data['color']);
    }
}
