<?php declare(strict_types=1);

namespace Badge\Core\Port\Driven\ForReadingRepositoryDetail;

use Badge\Core\Domain\Model\RepositoryDetail;

interface ForReadingRepositoryDetail
{
    public function readRepositoryDetail(string $packageName): RepositoryDetail;
}
