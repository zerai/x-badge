<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder;

interface GitHostingProviderInterface extends Builder
{
    public function addGithubAsHostingServiceProvider(): self;

    public function addBitbucketAsHostingServiceProvider(): self;
}
