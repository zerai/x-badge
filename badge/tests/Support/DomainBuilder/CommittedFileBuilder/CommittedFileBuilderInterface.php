<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\CommittedFileBuilder;

use Badge\Tests\Support\DomainBuilder\Builder;
use Badge\Tests\Support\DomainBuilder\HttpBuilderInterface;
use Badge\Tests\Support\DomainBuilder\OwnerShipInterface;

interface CommittedFileBuilderInterface extends Builder, OwnerShipInterface, HttpBuilderInterface
{
    public function addDefaultBranch(string $branchName): self;

    public function addComposerLockFileWithHttpStatusCode(int $httpStatusCode): self;

    public function addGitattributeFileWithHttpStatusCode(int $httpStatusCode): self;

    public function addGithubAsHostingServiceProvider(): self;

    public function addBitbucketAsHostingServiceProvider(): self;
}
