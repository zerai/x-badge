<?php declare(strict_types=1);

namespace Badge\Core\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile;

use Badge\Core\Domain\Model\RepositoryDetail;

interface ForDetectingRepositoryBranch
{
    public function getDefaultBranch(RepositoryDetail $repositoryDetail): string;
}
