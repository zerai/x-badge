<?php declare(strict_types=1);

namespace Badge\AdapterForObtainingBadgeContextValuesForCommittedFile;

use Badge\Core\Domain\Model\RepositoryDetail;
use Badge\Core\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\CommittedFileChecker as CommittedFileCheckerPort;
use Badge\Core\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\ForDetectingRepositoryBranch;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;

class CommittedFileChecker implements CommittedFileCheckerPort
{
    private const STATUS_COMMITTED = 200;

    private const STATUS_UNCOMMITTED = 404;

    private const STATUS_ERROR = 500;

    protected ClientInterface $httpClient;

    private ForDetectingRepositoryBranch $branchDetector;

    public function __construct(ClientInterface $httpClient, ForDetectingRepositoryBranch $branchDetector)
    {
        $this->httpClient = $httpClient;
        $this->branchDetector = $branchDetector;
    }

    public function checkFile(RepositoryDetail $repositoryDetail, string $filePath): int
    {
        $result = self::STATUS_ERROR;

        try {
            $defaultBranch = $this->branchDetector->getDefaultBranch($repositoryDetail);
        } catch (\Exception $exception) {
            return self::STATUS_ERROR;
        }

        $targetFileUrl = sprintf(
            '%s/%s/%s/%s',
            $repositoryDetail->repositoryUrl(),
            $repositoryDetail->repositoryPrefix(),
            $defaultBranch,
            $filePath
        );

        $fileStatus = $this->doRequest($targetFileUrl);

        if ($fileStatus === self::STATUS_COMMITTED || $fileStatus === self::STATUS_UNCOMMITTED) {
            $result = $fileStatus;
        }

        return $result;
    }

    protected function doRequest(string $url): int
    {
        $response = $this->httpClient->request(
            'HEAD',
            $url,
            [
                //RequestOptions::TIMEOUT => self::TIMEOUT_SECONDS,
                //RequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT_SECONDS,
                RequestOptions::HTTP_ERRORS => false,
            ]
        );

        return $response->getStatusCode();
    }
}
