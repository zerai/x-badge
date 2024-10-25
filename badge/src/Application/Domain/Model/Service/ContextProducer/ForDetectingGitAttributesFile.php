<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

interface ForDetectingGitAttributesFile
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function detectGitAttributes(string $packageName): int;
}
