<?php declare(strict_types=1);

namespace Badge\Tests\PHPUnitExtension;

use Badge\Tests\PHPUnitExtension\Assertions\IsCommittedComposerLockFileBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsCommittedGitAttributesFileBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsDailyDownloadsBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsDependentsBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsErrorComposerLockFileBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsErrorGitAttributesFileBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsMonthlyDownloadsBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsStableVersionBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsSuggestersBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsTotalDownloadsBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsUncommittedComposerLockFileBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsUncommittedGitAttributesFileBadgeContext;
use Badge\Tests\PHPUnitExtension\Assertions\IsUnstableVersionBadgeContext;

trait BadgeContextAssertionsTrait
{
    public static function assertIsCommittedComposerLockFileBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsCommittedComposerLockFileBadgeContext());
    }

    public static function assertIsUncommittedComposerLockFileBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsUncommittedComposerLockFileBadgeContext());
    }

    public static function assertIsErrorComposerLockFileBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsErrorComposerLockFileBadgeContext());
    }

    public static function assertIsDailyDownloadsBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsDailyDownloadsBadgeContext());
    }

    public static function assertIsDependentsBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsDependentsBadgeContext());
    }

    public static function assertIsCommittedGitAttributesBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsCommittedGitAttributesFileBadgeContext());
    }

    public static function assertIsUncommittedGitAttributesBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsUncommittedGitAttributesFileBadgeContext());
    }

    public static function assertIsErrorGitAttributesFileBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsErrorGitAttributesFileBadgeContext());
    }

    public static function assertIsMonthlyDownloadsBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsMonthlyDownloadsBadgeContext());
    }

    public static function assertIsStableVersionBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsStableVersionBadgeContext());
    }

    public static function assertIsSuggestersBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsSuggestersBadgeContext());
    }

    public static function assertIsTotalDownloadsBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsTotalDownloadsBadgeContext());
    }

    public static function assertIsUnstableVersionBadgeContext($value, string $message = ''): void
    {
        self::assertThat($value, new IsUnstableVersionBadgeContext());
    }
}
