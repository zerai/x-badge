<?php declare(strict_types=1);

namespace Badge\Core\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile;

use Badge\Core\Domain\Model\RepositoryDetail;

interface CommittedFileChecker
{
    public function checkFile(RepositoryDetail $repositoryDetail, string $filePath): int;
}
