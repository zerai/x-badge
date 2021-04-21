<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Adapter\Out\DefaultBranchDetector;
use Badge\Application\Domain\Model\RepositoryDetail;
use Badge\Infrastructure\Env;
use Badge\Tests\Support\ExtendedGithubClient\ExtendedForTestGithubClient;
use Bitbucket\Client as BitbucketClient;
use Github\Client as GithubClient;
use PHPUnit\Framework\TestCase;

/**
 * @group io-network
 */
final class GetDefaultBranchFromGithubApiTest extends TestCase
{
    /**
     * @var GithubClient
     */
    private $githubClient;

    /**
     * @var BitbucketClient
     */
    private $bitbucketClient;

    protected function setUp(): void
    {
        $this->githubClient = new ExtendedForTestGithubClient(Env::get('FAKE_GITHUB_API_URI'));

        $this->bitbucketClient = new BitbucketClient();
    }

    protected function tearDown(): void
    {
        $this->githubClient = null;

        $this->bitbucketClient = null;
    }

    /**
     * @test
     */
    public function canReadTheDefaltBrancheFromGithubApi(): void
    {
        self::markTestSkipped();
        $detector = new DefaultBranchDetector(
            $this->githubClient,
            $this->bitbucketClient,
        );

        $repositoryDetail = RepositoryDetail::fromRepositoryUrl('https://github.com/badges/poser');

        $result = $detector->getDefaultBranch($repositoryDetail);

        self::assertNotEmpty($result);
    }

    /**
     * @test
     */
    public function canReadTheDefaltBrancheFromBitbucketbApi(): void
    {
        self::markTestSkipped();
        $detector = new DefaultBranchDetector(
            $this->githubClient,
            $this->bitbucketClient,
        );

        $repositoryDetail = RepositoryDetail::fromRepositoryUrl('https://github.com/badges/poser');

        $result = $detector->getDefaultBranch($repositoryDetail);

        self::assertNotEmpty($result);
    }
}
