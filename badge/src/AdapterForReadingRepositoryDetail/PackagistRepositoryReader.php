<?php declare(strict_types=1);

namespace Badge\AdapterForReadingRepositoryDetail;

use Badge\Core\Domain\Model\RepositoryDetail;
use Badge\Core\Port\Driven\ForReadingRepositoryDetail\ForReadingRepositoryDetail;
use Packagist\Api\Client as PackagistClient;
use Packagist\Api\Result\Package;

final class PackagistRepositoryReader implements ForReadingRepositoryDetail
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
