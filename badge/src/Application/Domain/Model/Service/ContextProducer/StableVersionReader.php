<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

interface StableVersionReader
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readStableVersion(string $packageName): string;
}
