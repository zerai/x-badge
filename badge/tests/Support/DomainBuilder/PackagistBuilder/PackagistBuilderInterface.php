<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\PackagistBuilder;

use Badge\Tests\Support\DomainBuilder\Builder;
use Badge\Tests\Support\DomainBuilder\HttpBuilderInterface;
use Badge\Tests\Support\DomainBuilder\OwnerShipInterface;

interface PackagistBuilderInterface extends Builder, OwnerShipInterface, HttpBuilderInterface
{
    public function addSuggesters(int $suggesters): self;

    public function addDependents(int $dependents): self;

    public function addTotalDownloads(int $totalDownloads): self;

    public function addMonthlyDownloads(int $MonthlyDownloads): self;

    public function addDailyDownloads(int $DailyDownloads): self;

    public function addGithubAsHostingServiceProvider(): self;

    public function addBitbucketAsHostingServiceProvider(): self;
}
