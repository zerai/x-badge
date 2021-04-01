<?php declare(strict_types=1);

namespace Badge\Tests\Support\ExtendedGithubClient;

use Badge\Infrastructure\Env;
use Github\Exception\RuntimeException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @group io-network
 */
final class ExtendedForTestGithubClientTest extends TestCase
{
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
        self::expectExceptionMessage('Page not found');

        $extendedClient = new ExtendedForTestGithubClient(Env::get('FAKE_GITHUB_API_URI'));

        $extendedClient->repos()->show('foo', 'bar');
    }

    /**
     * @test
     */
    public function canReturnAStubedResponseForKnownRepository(): void
    {
        $extendedClient = new ExtendedForTestGithubClient(Env::get('FAKE_GITHUB_API_URI'));

        $response = $extendedClient->repos()->show('sebastianbergmann', 'phpunit');

        self::assertIsArray($response);
    }
}
