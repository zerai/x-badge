<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\CommittedFileBuilder;

use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\Data\GitDefaultBranchData;
use Badge\Tests\Support\DomainBuilder\Data\PackagistData;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilderInterface;
use RuntimeException;

final class CommittedFileBuilder implements CommittedFileBuilderInterface
{
    private const COMPOSER_LOCK_FILE = 'composer.lock';

    private const GITATTRIBUTE_FILE = '.gitattribute';

    private string $defaultBranch = '';

    private string $committedFile = '';

    private int $committedFileHttpStatusCode = 0;

    private PackagistBuilderInterface $packagistBuilder;

    private function __construct(PackagistBuilderInterface $packagistBuilder, string $defaultBranch = '', string $committedFile = '', int $committedFileHttpStatusCode = 0)
    {
        $this->packagistBuilder = $packagistBuilder;
        $this->defaultBranch = $defaultBranch;
        $this->committedFile = $committedFile;
        $this->committedFileHttpStatusCode = $committedFileHttpStatusCode;
    }

    public static function withVendorAndProjectName(string $vendorName, string $projectName): CommittedFileBuilderInterface
    {
        $packagistBuilder = PackagistBuilder::withVendorAndProjectName($vendorName, $projectName);

        return new self($packagistBuilder);
    }

    public function addGithubAsHostingServiceProvider(): CommittedFileBuilderInterface
    {
        $packagistBuilder = $this->packagistBuilder->addGithubAsHostingServiceProvider();

        return new self($packagistBuilder, $this->defaultBranch, $this->committedFile, $this->committedFileHttpStatusCode);
    }

    public function addBitbucketAsHostingServiceProvider(): CommittedFileBuilderInterface
    {
        $packagistBuilder = $this->packagistBuilder->addBitbucketAsHostingServiceProvider();

        return new self($packagistBuilder, $this->defaultBranch, $this->committedFile, $this->committedFileHttpStatusCode);
    }

    public function addDefaultBranch(string $branchName): CommittedFileBuilderInterface
    {
        $this->defaultBranch = $branchName;

        return new self($this->packagistBuilder, $this->defaultBranch, $this->committedFile, $this->committedFileHttpStatusCode);
    }

    public function addComposerLockFileWithHttpStatusCode(int $httpStatusCode): CommittedFileBuilderInterface
    {
        $this->committedFile = self::COMPOSER_LOCK_FILE;

        $this->committedFileHttpStatusCode = $httpStatusCode;

        return new self($this->packagistBuilder, $this->defaultBranch, $this->committedFile, $this->committedFileHttpStatusCode);
    }

    public function addGitattributeFileWithHttpStatusCode(int $httpStatusCode): CommittedFileBuilderInterface
    {
        $this->committedFile = self::GITATTRIBUTE_FILE;

        $this->committedFileHttpStatusCode = $httpStatusCode;

        return new self($this->packagistBuilder, $this->defaultBranch, $this->committedFile, $this->committedFileHttpStatusCode);
    }

    public function addHttpStatusCode(int $httpStatusCode): CommittedFileBuilderInterface
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function build(bool $useMockedServer = true)
    {
        $this->preBuildValidation();

        $defaultBranchData = new GitDefaultBranchData($this->getPackagistData()->repository(), $this->getPackagistData()->name(), $this->defaultBranch);

        if ($useMockedServer) {

            // create and populate fixuter request for get packagst data ()
            //ApiMockServer::loadPackagistFixtureByData($this->packagistData, $this->packagistData->httpStatusCode());

            $this->packagistBuilder->build();

            // create and populate fixture request for get default branche
            ApiMockServer::loadDefaultBranchFixtureByData($defaultBranchData->mockedEndPointForDefaultBranchRequest(), $defaultBranchData);

            // create and populate fixture request for committed file
            ApiMockServer::loadCommittedFileFixtureByData($defaultBranchData->mockedEndPointForCommittedFileRequest(), $defaultBranchData, $this->committedFileHttpStatusCode);

            return [
                'default-branch' => $this->defaultBranch,
                'default-branch-data' => $defaultBranchData,
                'committed-file' => $this->committedFile,
                'committed-file-http-status' => $this->committedFileHttpStatusCode,
                'packagist-data' => $this->getPackagistData(),
            ];
        }

        return [
            'default-branch' => $this->defaultBranch,
            'default-branch-data' => $defaultBranchData,
            'committed-file' => $this->committedFile,
            'committed-file-http-status' => $this->committedFileHttpStatusCode,
            'packagist-data' => $this->getPackagistData(),
        ];
    }

    private function preBuildValidation(): void
    {
        if ($this->defaultBranch === '') {
            throw new RuntimeException(
                'CommittedFile Builder error: Impossibele to build a committed file data without a git default branch.'
            );
        }

        if ($this->committedFileHttpStatusCode === 0) {
            throw new RuntimeException(
                'CommittedFile Builder error: Impossibele to build a committed file data without a http status code for committed file.'
            );
        }
    }

    private function getPackagistData(): PackagistData
    {
        return $this->packagistBuilder->build(false);
    }
}
