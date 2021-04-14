<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Infrastructure\Env;
use Badge\Infrastructure\ServiceContainer;
use Bitbucket\Client as BitbucketClient;
use Github\Client as GithubClient;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleHttpClient;
use Packagist\Api\Client as packagistClient;

final class ServiceContainerForAcceptanceTesting extends ServiceContainer
{
    protected ?GithubClient $githubClient = null;

    protected ?BitbucketClient $bitbucketClient = null;

    protected ?PackagistClient $packagistClient = null;

    protected ?GuzzleHttpClient $httpClient = null;

    public function __construct(GithubClient $githubClient = null, BitbucketClient $bitbucketClient = null, PackagistClient $packagistClient = null)
    {
        $this->githubClient = $githubClient ?? $githubClient;
        $this->bitbucketClient = $bitbucketClient ?? $bitbucketClient;
        $this->packagistClient = $packagistClient ?? $packagistClient;
    }

    protected function packagistApiClient(): PackagistClient
    {
        if ($this->packagistClient === null) {
            $clientForAcceptanceTest = new packagistClient();
            $clientForAcceptanceTest->setPackagistUrl(Env::get('FAKE_ALL_IN_ONE_API_URI'));
            $this->packagistClient = $clientForAcceptanceTest;
        }

        return $this->packagistClient;
    }

    protected function githubApiClient(): GithubClient
    {
        if ($this->githubClient === null) {
            $this->githubClient = new GithubClient();
        }

        return $this->githubClient;
    }

    protected function bitbucketApiClient(): BitbucketClient
    {
        if ($this->bitbucketClient === null) {
            $this->bitbucketClient = new BitbucketClient();
        }

        return $this->bitbucketClient;
    }

    protected function httpClient(): GuzzleHttpClient
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }
}
