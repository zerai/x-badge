<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\Common\CommittedFile;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\ContextValue\ComposerLockFile */
final class ComposerLockFileTest extends TestCase
{
    private const COLOR_COMMITTED = '#e60073';

    private const COLOR_UNCOMMITTED = '#99004d';

    private const COLOR_ERROR = '#aa0000';

    private const LOCK_COMMITTED = 'committed';

    private const LOCK_UNCOMMITTED = 'uncommitted';

    private const LOCK_ERROR = 'checking';

    private const SUBJECT = '.lock';

    private const SUBJECT_ERROR = 'Error';

    /**
     * @test
     */
    public function canBeCreatedAsCommitted(): void
    {
        $sut = ComposerLockFile::createAsCommitted();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(ComposerLockFile::class, $sut);
    }

    /**
     * @test
     */
    public function shouldReturnRenderingPropertiesOfAComposerLockCommittedFile(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT,
            'subject-value' => self::LOCK_COMMITTED,
            'color' => self::COLOR_COMMITTED,
        ];

        $renderingProperties = ComposerLockFile::createAsCommitted()->renderingProperties();

        self::assertEquals($expectedRenderingProperties, $renderingProperties);
    }

    /**
     * @test
     */
    public function canBeCreatedAsUncommitted(): void
    {
        $sut = ComposerLockFile::createAsUncommitted();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(ComposerLockFile::class, $sut);
    }

    /**
     * @test
     */
    public function shouldReturnRenderingPropertiesOfAComposerLockUncommittedFile(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT,
            'subject-value' => self::LOCK_UNCOMMITTED,
            'color' => self::COLOR_UNCOMMITTED,
        ];

        $renderingProperties = ComposerLockFile::createAsUncommitted()->renderingProperties();

        self::assertEquals($expectedRenderingProperties, $renderingProperties);
    }

    /**
     * @test
     */
    public function canBeCreatedAsError(): void
    {
        $sut = ComposerLockFile::createAsError();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(ComposerLockFile::class, $sut);
    }

    /**
     * @test
     */
    public function shouldReturnRenderingPropertiesOfAComposerLockWithError(): void
    {
        $expectedRenderingProperties = [
            'subject' => self::SUBJECT_ERROR,
            'subject-value' => self::LOCK_ERROR,
            'color' => self::COLOR_ERROR,
        ];

        $renderingProperties = ComposerLockFile::createAsError()->renderingProperties();

        self::assertEquals($expectedRenderingProperties, $renderingProperties);
    }
}
