<?php declare(strict_types=1);

namespace Badge\Application\Port\Driven\ForReadingBadgeContextValues;

interface ForReadingSuggesters
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readSuggesters(string $packageName): int;
}