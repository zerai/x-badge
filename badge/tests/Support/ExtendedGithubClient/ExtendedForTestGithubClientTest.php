<?php declare(strict_types=1);

namespace Badge\Tests\Support\ExtendedGithubClient;

use Github\Exception\RuntimeException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ExtendedForTestGithubClientTest extends TestCase
{
    private const FAKE_GITHUB_API_SERVER = '127.0.0.1:8081';

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

        $extendedClient = new ExtendedForTestGithubClient(self::FAKE_GITHUB_API_SERVER);

        $extendedClient->repos()->show('foo', 'bar');
    }

    /**
     * @test
     */
    public function canReturnAStubedResponseForKnownRepository(): void
    {
        $extendedClient = new ExtendedForTestGithubClient(self::FAKE_GITHUB_API_SERVER);

        $response = $extendedClient->repos()->show('sebastianbergmann', 'phpunit');

        self::assertIsArray($response);
    }
}
