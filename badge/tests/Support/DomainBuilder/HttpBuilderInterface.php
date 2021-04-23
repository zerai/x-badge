<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder;

interface HttpBuilderInterface
{
    public function addHttpStatusCode(int $httpStatusCode): Builder;
}
