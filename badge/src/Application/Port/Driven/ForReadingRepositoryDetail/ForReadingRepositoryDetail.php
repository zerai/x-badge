<?php declare(strict_types=1);

namespace Badge\Application\Port\Driven\ForReadingRepositoryDetail;

use Badge\Application\Domain\Model\RepositoryDetail;

interface ForReadingRepositoryDetail
{
    public function readRepositoryDetail(string $packageName): RepositoryDetail;
}
