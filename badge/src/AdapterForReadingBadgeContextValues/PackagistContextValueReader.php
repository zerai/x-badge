<?php declare(strict_types=1);

namespace Badge\AdapterForReadingBadgeContextValues;

use Badge\Application\PortOut\ContextValueReader;
use Badge\Infrastructure\ReleasedVersions;
use Packagist\Api\Client;
use Packagist\Api\Result\Package;

final class PackagistContextValueReader implements ContextValueReader
{
    private Client $packagistClient;

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

    public function readTotalDownloads(string $packageName): int
    {
        /** @var Package $package */
        $package = $this->packagistClient->get($packageName);

        return $package->getDownloads()->getTotal();
    }

    public function readMonthlyDownloads(string $packageName): int
    {
        /** @var Package $package */
        $package = $this->packagistClient->get($packageName);

        return $package->getDownloads()->getMonthly();
    }

    public function readDailyDownloads(string $packageName): int
    {
        /** @var Package $package */
        $package = $this->packagistClient->get($packageName);

        return $package->getDownloads()->getDaily();
    }

    public function readStableVersion(string $packageName): string
    {
        /** @var Package $package */
        $package = $this->packagistClient->get($packageName);

        return ReleasedVersions::fromApiData($package->getVersions())->stableVersion();
    }

    public function readUnstableVersion(string $packageName): string
    {
        /** @var Package $package */
        $package = $this->packagistClient->get($packageName);

        return ReleasedVersions::fromApiData($package->getVersions())->unstableVersion();
    }

    public function readLicense(string $packageName): string
    {
        /** @var Package $package */
        $package = $this->packagistClient->get($packageName);

        return ReleasedVersions::fromApiData($package->getVersions())->license();
    }
}
