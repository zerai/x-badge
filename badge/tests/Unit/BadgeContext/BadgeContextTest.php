<?php declare(strict_types=1);

namespace Badge\Tests\Unit\BadgeContext;

use Badge\Application\Domain\Model\BadgeContext\BadgeContext;
use Badge\Application\Domain\Model\BadgeContext\Color;
use Badge\Application\Domain\Model\BadgeContext\Subject;
use Badge\Application\Domain\Model\BadgeContext\SubjectValue;
use Badge\Application\Domain\Model\BadgeContext\Text;
use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;
use Badge\Tests\Support\FakeBadgeConfig;
use Badge\Tests\Support\IrrelevantBadgeConfig;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class BadgeContextTest extends TestCase
{
    public const DEFAULT_BADGE_SUBJECT = '-';

    public const DEFAULT_BADGE_SUBJECT_VALUE = '-';

    public const DEFAULT_BADGE_COLOR = '#7A7A7A';

    /**
     * @test
     */
    public function canBeCreatedAsDefaultContext(): void
    {
        $expectedSubject = new Subject(self::DEFAULT_BADGE_SUBJECT);
        $expectedSubjectValue = SubjectValue::fromArray(
            [
                'text' => self::DEFAULT_BADGE_SUBJECT_VALUE,
                'color' => self::DEFAULT_BADGE_COLOR,
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
     * @dataProvider committedComposerFileDataProvider
     * @dataProvider committedGitAttributesFileFileDataProvider
     * @param array<mixed> $expectedArray
     */
    public function canBeCreatedAFromContextValue(ContextualizableValue $inputContext, array $expectedArray): void
    {
        $sut = BadgeContext::FromContextValue($inputContext);

        $data = $sut->toArray();

        self::assertArrayHasKey('subject', $data);
        self::assertEquals($expectedArray['subject'], $data['subject']);
        self::assertArrayHasKey('subject-value', $data);
        self::assertEquals($expectedArray['subject-value'], $data['subject-value']);
        self::assertArrayHasKey('color', $data);
        self::assertEquals($expectedArray['color'], $data['color']);
    }

    /**
     * @psalm-return Generator<string, array{0: ComposerLockFile, 1: array{subject: string, subject-value: string, color: string}}, mixed, void>
     */
    public function committedComposerFileDataProvider(): Generator
    {
        yield 'committed .lock file' => [
            ComposerLockFile::createAsCommitted(),
            [
                'subject' => '.lock',
                'subject-value' => 'committed',
                'color' => '#e60073',
            ],
        ];
        yield 'uncommitted .lock file' => [
            ComposerLockFile::createAsUncommitted(),
            [
                'subject' => '.lock',
                'subject-value' => 'uncommitted',
                'color' => '#99004d',
            ],
        ];
        yield 'error .lock file' => [
            ComposerLockFile::createAsError(),
            [
                'subject' => 'Error',
                'subject-value' => 'checking',
                'color' => '#aa0000',
            ],
        ];
    }

    /**
     * @psalm-return Generator<string, array{0: GitAttributesFile, 1: array{subject: string, subject-value: string, color: string}}, mixed, void>
     */
    public function committedGitAttributesFileFileDataProvider(): Generator
    {
        yield 'committed .gitattributes file' => [
            GitAttributesFile::createAsCommitted(),
            [
                'subject' => '.gitattributes',
                'subject-value' => 'committed',
                'color' => '#96d490',
            ],
        ];
        yield 'uncommitted .gitattributes file' => [
            GitAttributesFile::createAsUncommitted(),
            [
                'subject' => '.gitattributes',
                'subject-value' => 'uncommitted',
                'color' => '#ad6c4b',
            ],
        ];
        yield 'error .gitattributes file' => [
            GitAttributesFile::createAsError(),
            [
                'subject' => 'Error',
                'subject-value' => 'checking',
                'color' => '#aa0000',
            ],
        ];
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
     * @param array{subject: string, subject-value: string, color: string} $constructionData
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
            'color' => '#000000',
        ]];

        yield 'empty subject-value' => [[
            'subject' => 'foo',
            'subject-value' => '',
            'color' => '#000000',
        ]];

        yield 'empty color' => [[
            'subject' => 'foo',
            'subject-value' => 'foo',
            'color' => '',
        ]];
    }
}
