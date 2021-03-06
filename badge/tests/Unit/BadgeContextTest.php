<?php declare(strict_types=1);

namespace Badge\Tests\Unit;

use Badge\Application\Domain\Model\DefaultBadgeConfig;
use Badge\Application\Domain\Model\ValueObject\BadgeContext;
use Badge\Application\Domain\Model\ValueObject\Color;
use Badge\Application\Domain\Model\ValueObject\Subject;

use Badge\Application\Domain\Model\ValueObject\SubjectValue;

use Badge\Application\Domain\Model\ValueObject\Text;
use Badge\Tests\Support\FakeBadgeConfig;
use Badge\Tests\Support\IrrelevantBadgeConfig;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class BadgeContextTest extends TestCase
{
    /**
     * @test
     */
    public function canBeCreatedAsDefaultContext(): void
    {
        $expectedSubject = new Subject(DefaultBadgeConfig::SUBJECT);
        $expectedSubjectValue = SubjectValue::fromArray(
            [
                'text' => DefaultBadgeConfig::SUBJECT_VALUE,
                'color' => DefaultBadgeConfig::COLOR,
            ]
        );

        $sut = BadgeContext::asDefault();

        self::assertInstanceOf(Subject::class, $sut->subject());
        self::assertEquals($expectedSubject, $sut->subject());
        self::assertInstanceOf(SubjectValue::class, $sut->subjectValue());
        self::assertEquals($expectedSubjectValue, $sut->subjectValue());
        self::assertInstanceOf(Text::class, $sut->subjectValue()->text());
        self::assertInstanceOf(Color::class, $sut->subjectValue()->color());
    }

    /**
     * @test
     */
    public function canBeCreatedfromArray(): void
    {
        $sut = BadgeContext::fromArray([
            'subject' => IrrelevantBadgeConfig::SUBJECT,
            'subject-value' => IrrelevantBadgeConfig::SUBJECT_VALUE,
            'color' => IrrelevantBadgeConfig::COLOR,
        ]);

        self::assertEquals(IrrelevantBadgeConfig::SUBJECT, $sut->subject()->value());
        self::assertEquals(IrrelevantBadgeConfig::SUBJECT_VALUE, $sut->subjectValue()->text()->value());
        self::assertEquals(IrrelevantBadgeConfig::COLOR, $sut->subjectValue()->color()->value());
    }

    /**
     * @test
     */
    public function canBeCompared(): void
    {
        $fristBadgeContext = BadgeContext::fromArray(IrrelevantBadgeConfig::AS_CONSTRUCTOR_ARRAY);
        $secondBadgeContext = BadgeContext::fromArray(FakeBadgeConfig::AS_CONSTRUCTOR_ARRAY);
        $copyOfFristBadgeContext = BadgeContext::fromArray(IrrelevantBadgeConfig::AS_CONSTRUCTOR_ARRAY);

        self::assertTrue($fristBadgeContext->equals($copyOfFristBadgeContext));
        self::assertFalse($fristBadgeContext->equals($secondBadgeContext));
        self::assertFalse($secondBadgeContext->equals($copyOfFristBadgeContext));
    }

    /**
     * @test
     * @dataProvider emptyValueDataProvider
     * @param array{'subject': string, 'subject-value': string, "color": string} $constructionData
     */
    public function cantBeCreatedfromArrayShouldThrowException(array $constructionData): void
    {
        self::expectException(InvalidArgumentException::class);

        BadgeContext::fromArray($constructionData);
    }

    /**
     * @psalm-return Generator<string, array{0: array{subject: string, subject-value: string, color: string}}, mixed, void>
     */
    public function emptyValueDataProvider(): Generator
    {
        yield 'empty subject' => [[
            'subject' => 'foo',
            'subject-value' => '',
            'color' => '000000',
        ]];

        yield 'empty subject-value' => [[
            'subject' => 'foo',
            'subject-value' => '',
            'color' => '000000',
        ]];

        yield 'empty color' => [[
            'subject' => 'foo',
            'subject-value' => 'foo',
            'color' => '',
        ]];
    }
}
