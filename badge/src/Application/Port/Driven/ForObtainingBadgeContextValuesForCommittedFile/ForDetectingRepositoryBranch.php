<?php declare(strict_types=1);

namespace Badge\Application\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile;

use Badge\Application\Domain\Model\RepositoryDetail;

interface ForDetectingRepositoryBranch
{
    public function getDefaultBranch(RepositoryDetail $repositoryDetail): string;
}
