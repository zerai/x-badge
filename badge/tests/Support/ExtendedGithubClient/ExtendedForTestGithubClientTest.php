<?php declare(strict_types=1);

namespace Badge\Tests\Support\ExtendedGithubClient;

use Badge\Infrastructure\Env;
use Badge\Tests\Support\ExtendedGithubClient\ApiMockServer\ApiMockServer;
use Github\Exception\RuntimeException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @group io-network
 * @covers \Badge\Tests\Support\ExtendedGithubClient\ExtendedForTestGithubClient
 */
final class ExtendedForTestGithubClientTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        self::loadFixtureToApiMockServer();
    }

    public static function tearDownAfterClass(): void
    {
        ApiMockServer::reset();
    }

    /**
     * @test
     */
    public function unkownFakeServerUrishouldThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('ExtendedGithubClient error: empty URI is not allowed.');

        new ExtendedForTestGithubClient();
    }

    /**
     * @test
     */
    public function unknownRepositoryReturnErrorNotFound(): void
    {
        self::expectException(RuntimeException::class);

        $extendedClient = new ExtendedForTestGithubClient(Env::get('API_MOCK_SERVER'));

        $extendedClient->repos()->show('foo', 'bar');
    }

    /**
     * @test
     */
    public function canReturnAStubedResponseForKnownRepository(): void
    {
        $extendedClient = new ExtendedForTestGithubClient(Env::get('API_MOCK_SERVER'));

        $response = $extendedClient->repos()->show('sebastianbergmann', 'phpunit');

        self::assertNotEmpty($response);
    }

    private static function loadFixtureToApiMockServer(): void
    {
        ApiMockServer::loadFixture(
            '/repos/sebastianbergmann/phpunit',
            self::getFixtureContent(__DIR__ . '/repository-sebastianbergmann-phpunit.json')
        );
    }

    private static function getFixtureContent(string $fixtureFile): string
    {
        if (! file_exists($fixtureFile)) {
            throw new RuntimeException(sprintf('Fixture file: %s not found.', $fixtureFile));
        }

        /** @phpstan-ignore-next-line */
        return file_get_contents($fixtureFile);
    }
}
