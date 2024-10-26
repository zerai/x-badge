<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service;

use Badge\Application\Domain\Model\RepositoryDetail;

interface ForReadingRepositoryDetail
{
    public function readRepositoryDetail(string $packageName): RepositoryDetail;
}
