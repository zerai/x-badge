<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Adapter\Out\DefaultBranchDetector;
use Badge\Application\Domain\Model\RepositoryDetail;
use Badge\Infrastructure\Env;
use Badge\Tests\Integration\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\ExtendedGithubClient\ExtendedForTestGithubClient;
use Bitbucket\Client as BitbucketClient;
use Generator;
use Github\Client as GithubClient;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @group io-network
 * @covers \Badge\Adapter\Out\DefaultBranchDetector
 */
final class GetDefaultBranchFromGithubApiTest extends TestCase
{
    private GithubClient $githubClient;

    private BitbucketClient $bitbucketClient;

    public static function setUpBeforeClass(): void
    {
        self::loadFixtureToApiMockServer();
    }

    public static function tearDownAfterClass(): void
    {
        ApiMockServer::reset();
    }

    protected function setUp(): void
    {
        $this->githubClient = new ExtendedForTestGithubClient(Env::get('API_MOCK_SERVER'));

        $client = new BitbucketClient();
        $client->setUrl(Env::get('API_MOCK_SERVER'));
        $this->bitbucketClient = $client;
    }

    protected function tearDown(): void
    {
        $this->githubClient = null;

        $this->bitbucketClient = null;
    }

    /**
     * @test
     * @dataProvider githubRepositoryUrlDataprovider
     */
    public function canReadTheDefaltBrancheFromGithubApi(string $repositoryUrl): void
    {
        $detector = new DefaultBranchDetector(
            $this->githubClient,
            $this->bitbucketClient,
        );

        $repositoryDetail = RepositoryDetail::fromRepositoryUrl($repositoryUrl);

        $result = $detector->getDefaultBranch($repositoryDetail);

        self::assertNotEmpty($result);
    }

    /**
     * @test
     * @dataProvider bitbucketRepositoryUrlDataprovider
     */
    public function canReadTheDefaltBrancheFromBitbucketbApi(string $repositoryUrl): void
    {
        $detector = new DefaultBranchDetector(
            $this->githubClient,
            $this->bitbucketClient,
        );

        $repositoryDetail = RepositoryDetail::fromRepositoryUrl($repositoryUrl);

        $result = $detector->getDefaultBranch($repositoryDetail);

        self::assertNotEmpty($result);
    }

    /**
     * @return Generator<string, array<int, string>>
     */
    public function githubRepositoryUrlDataprovider(): Generator
    {
        yield 'repository badges/poser' => ['https://github.com/badges/poser'];
        yield 'repository doctrine/collections' => ['https://github.com/doctrine/collections'];
        yield 'repository seldaek/monolog' => ['https://github.com/seldaek/monolog'];
    }

    /**
     * @return Generator<string, array<int, string>>
     */
    public function bitbucketRepositoryUrlDataprovider(): Generator
    {
        yield 'repository rickymcalister/instagram-php-wrapper' => ['https://bitbucket.org/rickymcalister/instagram-php-wrapper'];
        yield 'repository axtens/php-bignum' => ['https://bitbucket.org/axtens/php-bignum'];
        yield 'repository masnun/php-feeds' => ['https://bitbucket.org/masnun/php-feeds'];
    }

    private static function loadFixtureToApiMockServer(): void
    {
        ApiMockServer::loadPackagistFixtureForPackage(
            '/repos/badges/poser',
            self::getFixtureContent(__DIR__ . '/Fixture/Github/repository-badges-poser.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/repos/doctrine/collections',
            self::getFixtureContent(__DIR__ . '/Fixture/Github/repository-doctrine-collections.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/repos/seldaek/monolog',
            self::getFixtureContent(__DIR__ . '/Fixture/Github/repository-seldaek-monolog.json')
        );

        ApiMockServer::loadPackagistFixtureForPackage(
            '/2.0/repositories/rickymcalister/instagram-php-wrapper',
            self::getFixtureContent(__DIR__ . '/Fixture/Bitbucket/repository-rickymcalister-instagram-php-wrapper.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/2.0/repositories/axtens/php-bignum',
            self::getFixtureContent(__DIR__ . '/Fixture/Bitbucket/repository-axtens-php-bignum.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/2.0/repositories/masnun/php-feeds',
            self::getFixtureContent(__DIR__ . '/Fixture/Bitbucket/repository-axtens-php-bignum.json')
        );
    }

    private static function getFixtureContent(string $fixtureFile): string
    {
        if (! \file_exists($fixtureFile)) {
            throw new RuntimeException(\sprintf('Fixture file: %s not found.', $fixtureFile));
        }

        /** @phpstan-ignore-next-line */
        return \file_get_contents($fixtureFile);
    }
}
