<?php declare(strict_types=1);

namespace Badge\Core\Port\Driven\ForReadingBadgeContextValues;

interface ForReadingDependents
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readDependents(string $packageName): int;
}
