<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension\Assertions;

use Generator;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Tests\PHPUnitExtension\Assertions\IsUnstableVersionBadgeContext */
final class IsUnstableVersionBadgeContextTest extends TestCase
{
    private const COLOR_UNSTABLE = 'e68718';

    private const SUBJECT_UNSTABLE = 'unstable';

    private IsUnstableVersionBadgeContext $constraint;

    protected function setUp(): void
    {
        $this->constraint = new IsUnstableVersionBadgeContext();
    }

    /**
     * @test
     */
    public function shouldBeTrue(): void
    {
        $validArray = [
            'subject' => self::SUBJECT_UNSTABLE,
            'subject-value' => 'irrelevant',
            'color' => self::COLOR_UNSTABLE,
        ];

        $this->assertTrue($this->constraint->matches($validArray));
    }

    /**
     * @test
     * @dataProvider invalidTypeDataProvider
     * @param array<mixed> $input
     */
    public function invalidArgument($input): void
    {
        $this->assertFalse($this->constraint->matches($input));
    }

    public function invalidTypeDataProvider(): Generator
    {
        yield [null];
        yield ['null'];
        yield [0];
        yield [0.5];
        yield [-1];
        yield [''];
        yield ['foo'];
    }

    /**
     * @test
     * @dataProvider invalidArrayDataProvider
     * @dataProvider invalidKeysInArrayDataProvider
     * @dataProvider invalidValueInArrayDataProvider
     * @param array<mixed> $input
     */
    public function shouldBeFalseForInvalidArray($input): void
    {
        $this->assertFalse($this->constraint->matches($input));
    }

    public function invalidArrayDataProvider(): Generator
    {
        yield [[]];
        yield [[[]]];
        yield [[0, 1, 2, 3]];
        yield [['']];
        yield [['foo', 'bar']];
    }

    public function invalidKeysInArrayDataProvider(): Generator
    {
        yield [[
            'no-key subject' => self::SUBJECT_UNSTABLE,
            'subject-value' => 'irrelevant',
            'color' => 'irrelevant',
        ]];
        yield [[
            'subject' => self::SUBJECT_UNSTABLE,
            'no-key-subject-value' => 'irrelevant',
            'color' => 'irrelevant',
        ]];
        yield [[
            'subject' => self::SUBJECT_UNSTABLE,
            'subject-value' => 'irrelevant',
            'no-key-color' => 'irrelevant',
        ]];
    }

    public function invalidValueInArrayDataProvider(): Generator
    {
        yield [[
            'subject' => null,
            'subject-value' => null,
            'color' => null,
        ]];
        yield [[
            'subject' => 0,
            'subject-value' => 0,
            'color' => 0,
        ]];
        yield [[
            'subject' => '',
            'subject-value' => '',
            'color' => '',
        ]];
        yield [[
            'subject' => [],
            'subject-value' => [],
            'color' => [],
        ]];
    }
}
