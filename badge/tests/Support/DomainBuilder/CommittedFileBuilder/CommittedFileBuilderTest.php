<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\CommittedFileBuilder;

use Badge\Tests\Support\DomainBuilder\Data\GitDefaultBranchData;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/** @covers \Badge\Tests\Support\DomainBuilder\CommittedFileBuilder\CommittedFileBuilder */
final class CommittedFileBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldAddDefaultBranchForGithubRepository(): void
    {
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('github-master')
            ->addComposerLockFileWithHttpStatusCode(200);

        $data = $builder->build(false);

        $defaultBranchData = $data['default-branch-data'];

        self::assertEquals('github-master', $data['default-branch']);
        self::assertInstanceOf(GitDefaultBranchData::class, $defaultBranchData);
        self::assertEquals('github-master', $defaultBranchData->defaultBranch());
        self::assertEquals('vendorname/projectname', $defaultBranchData->repositoryName());
        self::assertEquals('/repos/vendorname/projectname', $defaultBranchData->mockedEndPointForDefaultBranchRequest());
    }

    /**
     * @test
     */
    public function itShouldAddDefaultBranchForBitbucketRepository(): void
    {
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addBitbucketAsHostingServiceProvider()
            ->addDefaultBranch('bitbucket-main')
            ->addComposerLockFileWithHttpStatusCode(200);

        $data = $builder->build(false);

        $defaultBranchData = $data['default-branch-data'];

        self::assertEquals('bitbucket-main', $data['default-branch']);
        self::assertInstanceOf(GitDefaultBranchData::class, $defaultBranchData);
        self::assertEquals('bitbucket-main', $defaultBranchData->defaultBranch());
        self::assertEquals('vendorname/projectname', $defaultBranchData->repositoryName());
    }

    /**
     * @test
     */
    public function itShouldFailIfThereIsNotADefaultBranch(): void
    {
        $this->expectException(RuntimeException::class);

        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider();

        $builder->build(false);
    }

    /**
     * @test
     */
    public function itShouldAddComposerLockFileWithAHttpstatusCode(): void
    {
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('master')
            ->addComposerLockFileWithHttpStatusCode(200);

        $data = $builder->build(false);

        $defaultBranchData = $data['default-branch-data'];

        self::assertEquals('master', $data['default-branch']);
        self::assertEquals('composer.lock', $data['committed-file']);
        self::assertEquals('composer.lock', $defaultBranchData->committedFile());
        self::assertEquals(200, $data['committed-file-http-status']);
    }

    /**
     * @test
     */
    public function itShouldAddGitAttributeFileWithAHttpstatusCode(): void
    {
        $builder = CommittedFileBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addDefaultBranch('main')
            ->addGitattributeFileWithHttpStatusCode(200);

        $data = $builder->build(false);

        $defaultBranchData = $data['default-branch-data'];

        self::assertEquals('main', $data['default-branch']);
        self::assertEquals('.gitattributes', $data['committed-file']);
        self::assertEquals('.gitattributes', $defaultBranchData->committedFile());
        self::assertEquals(200, $data['committed-file-http-status']);
    }
}
