<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

interface LicenseReader
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readLicense(string $packageName): string;
}
