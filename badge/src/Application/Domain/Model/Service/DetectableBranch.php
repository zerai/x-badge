<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service;

use Badge\Application\Domain\Model\RepositoryDetail;

interface DetectableBranch
{
    public function getDefaultBranch(RepositoryDetail $repositoryDetail): string;
}
