<?php declare(strict_types=1);

namespace Badge\Tests\Support\ExtendedGithubClient;

use Github\Client;
use Github\HttpClient\Builder;
use Github\HttpClient\Plugin\GithubExceptionThrower;
use Github\HttpClient\Plugin\History;
use Http\Discovery\UriFactoryDiscovery;
use InvalidArgumentException;

class ExtendedForTestGithubClient extends Client
{
    /**
     * @var string
     */
    private $apiVersion;

    /**
     * @var Builder
     */
    private $httpClientBuilder;

    /**
     * @var History
     */
    private $responseHistory;

    /**
     * Instantiate a new GitHub client.
     *
     * @param string|null  $apiVersion
     * @param string|null  $enterpriseUrl
     */
    public function __construct(string $fakeServerBaseUri = '', Builder $httpClientBuilder = null, $apiVersion = null, $enterpriseUrl = null)
    {
        if ($fakeServerBaseUri === '') {
            throw new InvalidArgumentException('ExtendedGithubClient error: empty URI is not allowed.');
        }
        $this->responseHistory = new History();
        $this->httpClientBuilder = $builder = $httpClientBuilder ?: new Builder();

        $builder->addPlugin(new GithubExceptionThrower());
        $builder->addPlugin(new \Http\Client\Common\Plugin\HistoryPlugin($this->responseHistory));
        $builder->addPlugin(new \Http\Client\Common\Plugin\RedirectPlugin());
        /** @psalm-suppress DeprecatedClass */
        $builder->addPlugin(new \Http\Client\Common\Plugin\AddHostPlugin(UriFactoryDiscovery::find()->createUri($fakeServerBaseUri)));
        $builder->addPlugin(new \Http\Client\Common\Plugin\HeaderDefaultsPlugin([
            'User-Agent' => 'php-github-api (http://github.com/KnpLabs/php-github-api)',
        ]));

        $this->apiVersion = $apiVersion ?: 'v3';
        $builder->addHeaderValue('Accept', \sprintf('application/vnd.github.%s+json', $this->apiVersion));

        if ($enterpriseUrl) {
            /** @phpstan-ignore-next-line */ /** @psalm-suppress UndefinedMagicMethod */
            $this->setEnterpriseUrl($enterpriseUrl);
        }
    }

    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * @return Builder
     */
    protected function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }
}
