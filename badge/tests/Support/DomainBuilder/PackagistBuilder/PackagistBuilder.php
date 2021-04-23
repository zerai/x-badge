<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\PackagistBuilder;

use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\Data\PackagistData;
use RuntimeException;

final class PackagistBuilder implements PackagistBuilderInterface
{
    private PackagistData $packagistData;

    private function __construct(PackagistData $data)
    {
        $this->packagistData = $data;
    }

    public static function withVendorAndProjectName(string $vendorName, string $projectName): PackagistBuilderInterface
    {
        $name = \sprintf('%s/%s', $vendorName, $projectName);

        $data = new PackagistData($name);

        return new self($data);
    }

    public function addSuggesters(int $suggesters): PackagistBuilderInterface
    {
        $this->packagistData = $this->packagistData->withSuggesters($suggesters);

        return new self($this->packagistData);
    }

    public function addDependents(int $dependents): PackagistBuilderInterface
    {
        $this->packagistData = $this->packagistData->withDependents($dependents);

        return new self($this->packagistData);
    }

    public function addTotalDownloads(int $totalDownloads): PackagistBuilderInterface
    {
        $this->packagistData = $this->packagistData->withTotals($totalDownloads);

        return new self($this->packagistData);
    }

    public function addMonthlyDownloads(int $MonthlyDownloads): PackagistBuilderInterface
    {
        $this->packagistData = $this->packagistData->withMonthly($MonthlyDownloads);

        return new self($this->packagistData);
    }

    public function addDailyDownloads(int $DailyDownloads): PackagistBuilderInterface
    {
        $this->packagistData = $this->packagistData->withDaily($DailyDownloads);

        return new self($this->packagistData);
    }

    public function addGithubAsHostingServiceProvider(): PackagistBuilderInterface
    {
        $this->packagistData =
            $this->packagistData
                ->withRepository(
                    \sprintf('https://github.com/%s', $this->packagistData->name())
                );

        return new self($this->packagistData);
    }

    public function addBitbucketAsHostingServiceProvider(): PackagistBuilderInterface
    {
        $this->packagistData =
            $this->packagistData
                ->withRepository(
                    \sprintf('https://bitbucket.org/%s', $this->packagistData->name())
                );

        return new self($this->packagistData);
    }

    public function addHttpStatusCode(int $httpStatusCode): PackagistBuilderInterface
    {
        $this->packagistData = $this->packagistData->withHttpStatusCode($httpStatusCode);

        return new self($this->packagistData);
    }

    public function build(bool $useMockedServer = true): PackagistData
    {
        $this->preBuildValidation();

        if ($useMockedServer) {
            ApiMockServer::loadPackagistFixtureByData($this->packagistData, $this->packagistData->httpStatusCode());

            return $this->packagistData;
        }

        return $this->packagistData;
    }

    private function preBuildValidation(): void
    {
        if ($this->packagistData->repository() === '') {
            throw new RuntimeException(
                'Packagist Builder error: Impossibele to build a packagist data without an git hosting service provider.'
            );
        }
    }
}
