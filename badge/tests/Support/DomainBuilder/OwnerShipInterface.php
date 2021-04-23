<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder;

interface OwnerShipInterface extends Builder
{
    public static function withVendorAndProjectName(string $vendorName, string $projectName): Builder;
}
