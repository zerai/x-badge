<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\Common\CommittedFile;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\ContextValue\GitAttributesFile */
final class GitAttributesFileTest extends TestCase
{
    private const COLOR_COMMITTED = '#96d490';

    private const COLOR_UNCOMMITTED = '#ad6c4b';

    private const COLOR_ERROR = '#aa0000';

    private const GITATTRIBUTES_COMMITTED = 'committed';

    private const GITATTRIBUTES_UNCOMMITTED = 'uncommitted';

    private const GITATTRIBUTES_ERROR = 'checking';

    private const SUBJECT = '.gitattributes';

    private const SUBJECT_ERROR = 'Error';

    /**
     * @test
     */
    public function canBeCreatedAsCommitted(): void
    {
        $sut = GitAttributesFile::createAsCommitted();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(GitAttributesFile::class, $sut);
    }

    /**
     * @test
     */
    public function shouldReturnRenderingPropertiesOfAGitattributesCommittedFile(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT,
            'subject-value' => self::GITATTRIBUTES_COMMITTED,
            'color' => self::COLOR_COMMITTED,
        ];

        $renderingProperties = GitAttributesFile::createAsCommitted()->renderingProperties();

        self::assertEquals($expectedRenderingProperties, $renderingProperties);
    }

    /**
     * @test
     */
    public function canBeCreatedAsUncommitted(): void
    {
        $sut = GitAttributesFile::createAsUncommitted();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(GitAttributesFile::class, $sut);
    }

    /**
     * @test
     */
    public function shouldReturnRenderingPropertiesOfAGitattributesUncommittedFile(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT,
            'subject-value' => self::GITATTRIBUTES_UNCOMMITTED,
            'color' => self::COLOR_UNCOMMITTED,
        ];

        $renderingProperties = GitAttributesFile::createAsUncommitted()->renderingProperties();

        self::assertEquals($expectedRenderingProperties, $renderingProperties);
    }

    /**
     * @test
     */
    public function canBeCreatedAsError(): void
    {
        $sut = GitAttributesFile::createAsError();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(GitAttributesFile::class, $sut);
    }

    /**
     * @test
     */
    public function shouldReturnRenderingPropertiesOfAGitattributesWithErrorFile(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT_ERROR,
            'subject-value' => self::GITATTRIBUTES_ERROR,
            'color' => self::COLOR_ERROR,
        ];

        $renderingProperties = GitAttributesFile::createAsError()->renderingProperties();

        self::assertEquals($expectedRenderingProperties, $renderingProperties);
    }
}
