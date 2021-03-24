<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\DefaultBranchDetector;

use Badge\Application\Domain\Model\RepositoryDetail;

interface DefaultBranchDetector
{
    public function getDefaultBranch(RepositoryDetail $repositoryDetail): string;
}
