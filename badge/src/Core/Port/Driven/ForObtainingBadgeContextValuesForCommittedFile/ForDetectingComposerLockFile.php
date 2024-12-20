<?php declare(strict_types=1);

namespace Badge\Core\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile;

interface ForDetectingComposerLockFile
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function detectComposerLock(string $packageName): int;
}
