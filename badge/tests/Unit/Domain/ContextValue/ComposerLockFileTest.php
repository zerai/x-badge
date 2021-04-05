<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\Common\CommittedFile;
use Badge\Application\Domain\Model\ContextValue\ComposerLockFile;
use PHPUnit\Framework\TestCase;

final class ComposerLockFileTest extends TestCase
{
    /**
     * @test
     */
    public function canBeCreated(): void
    {
        $sut = new ComposerLockFile('committed');
        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(ComposerLockFile::class, $sut);

        $sut = ComposerLockFile::createAsCommitted();
        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(ComposerLockFile::class, $sut);
    }

    /**
     * @test
     */
    public function canBeCreatedAsCommittedWithFactoryMethod(): void
    {
        $sut = ComposerLockFile::createAsCommitted();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(ComposerLockFile::class, $sut);
    }

    /**
     * @test
     */
    public function canBeCreatedAsUncommittedWithFactoryMethod(): void
    {
        $sut = ComposerLockFile::createAsUncommitted();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(ComposerLockFile::class, $sut);
    }

    /**
     * @test
     */
    public function canBeCreatedAsErrorFactoryMethod(): void
    {
        $sut = ComposerLockFile::createAsError();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(ComposerLockFile::class, $sut);
    }
}
