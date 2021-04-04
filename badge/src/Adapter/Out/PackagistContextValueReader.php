<?php declare(strict_types=1);

namespace Badge\Adapter\Out;

use Badge\Application\PortOut\ContextValueReader;
use Packagist\Api\Client;
use Packagist\Api\Result\Package;

final class PackagistContextValueReader implements ContextValueReader
{
    /**
     * @var Client
     */
    private $packagistClient;

    public function __construct(Client $packagistClient)
    {
        $this->packagistClient = $packagistClient;
    }

    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readSuggesters(string $packageName): int
    {
        /** @var Package $package */
        $package = $this->packagistClient->get($packageName);

        return $package->getSuggesters();
    }

    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readDependents(string $packageName): int
    {
        /** @var Package $package */
        $package = $this->packagistClient->get($packageName);

        return $package->getDependents();
    }
}
