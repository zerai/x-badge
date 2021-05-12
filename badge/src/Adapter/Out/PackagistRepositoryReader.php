<?php declare(strict_types=1);

namespace Badge\Adapter\Out;

use Badge\Application\Domain\Model\RepositoryDetail;
use Badge\Application\Domain\Model\Service\RepositoryDetailReader;
use Packagist\Api\Client as PackagistClient;
use Packagist\Api\Result\Package;

final class PackagistRepositoryReader implements RepositoryDetailReader
{
    private PackagistClient $packagistClient;

    public function __construct(PackagistClient $packagistClient)
    {
        $this->packagistClient = $packagistClient;
    }

    public function readRepositoryDetail(string $packageName): RepositoryDetail
    {
        /** @var Package $packageInfo */
        $packageInfo = $this->packagistClient->get($packageName);

        return RepositoryDetail::fromRepositoryUrl($packageInfo->getRepository());
    }
}
