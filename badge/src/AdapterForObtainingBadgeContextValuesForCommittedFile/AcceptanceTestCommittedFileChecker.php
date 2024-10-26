<?php declare(strict_types=1);

namespace Badge\AdapterForObtainingBadgeContextValuesForCommittedFile;

use Badge\Application\Port\Driven\ForObtainingBadgeContextValuesForCommittedFile\ForDetectingRepositoryBranch;
use Badge\Infrastructure\Env;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;

/**
 * @codeCoverageIgnore
 */
class AcceptanceTestCommittedFileChecker extends CommittedFileChecker
{
    protected ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient, ForDetectingRepositoryBranch $branchDetector)
    {
        parent::__construct($httpClient, $branchDetector);
        $this->httpClient = $httpClient;
    }

    protected function doRequest(string $url): int
    {
        $url = Env::get('API_MOCK_SERVER') . parse_url($url, PHP_URL_PATH);

        $response = $this->httpClient->request(
            'HEAD',
            $url,
            [
                RequestOptions::HTTP_ERRORS => false,
            ]
        );

        return $response->getStatusCode();
    }
}
