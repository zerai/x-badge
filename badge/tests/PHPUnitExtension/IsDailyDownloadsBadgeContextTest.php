<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension;

use Generator;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Tests\PHPUnitExtension\IsDailyDownloadsBadgeContext */
final class IsDailyDownloadsBadgeContextTest extends TestCase
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'downloads';

    private string $suffix = ' today';

    private IsDailyDownloadsBadgeContext $constraint;

    protected function setUp(): void
    {
        $this->constraint = new IsDailyDownloadsBadgeContext();
    }

    /**
     * @test
     */
    public function shouldBeTrue(): void
    {
        $validArray = [
            'subject' => self::SUBJECT,
            'subject-value' => '50 today',
            'color' => self::COLOR,
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
            'no-key subject' => self::SUBJECT,
            'subject-value' => 'irrelevant',
            'color' => 'irrelevant',
        ]];
        yield [[
            'subject' => self::SUBJECT,
            'no-key-subject-value' => 'irrelevant',
            'color' => 'irrelevant',
        ]];
        yield [[
            'subject' => self::SUBJECT,
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
