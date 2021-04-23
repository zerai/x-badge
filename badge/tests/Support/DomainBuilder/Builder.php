<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder;

interface Builder
{
    /**
     * @return mixed
     */
    public function build(bool $useMockedServer = true);
}
