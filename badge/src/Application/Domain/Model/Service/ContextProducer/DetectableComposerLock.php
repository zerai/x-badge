<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

interface DetectableComposerLock
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function detectComposerLock(string $packageName): int;
}
