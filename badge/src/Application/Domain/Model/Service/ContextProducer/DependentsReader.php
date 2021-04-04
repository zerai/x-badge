<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

interface DependentsReader
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readDependents(string $packageName): int;
}
