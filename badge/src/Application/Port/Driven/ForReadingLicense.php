<?php declare(strict_types=1);

namespace Badge\Application\Port\Driven;

interface ForReadingLicense
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readLicense(string $packageName): string;
}
