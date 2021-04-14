<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain;

use Badge\Application\Domain\Model\RepositoryDetail;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\RepositoryDetail */
final class RepositoryDetailTest extends TestCase
{
    private const GITHUB_REPOSITORY_URL = 'https://github.com/sebastianbergmann/phpunit';

    private const BITBUCKET_REPOSITORY_URL = 'https://bitbucket.org/sebastianbergmann/phpunit';

    /**
     * @test
     * @dataProvider validRepositoryUrlDataProvider
     */
    public function canBeCreatedFromRepositoryUrl(string $repositoryUrl): void
    {
        self::assertInstanceOf(RepositoryDetail::class, RepositoryDetail::fromRepositoryUrl($repositoryUrl));
    }

    /**
     * @psalm-return Generator<string, string[]>
     */
    public function validRepositoryUrlDataProvider(): Generator
    {
        yield 'github address' => [self::GITHUB_REPOSITORY_URL];
        yield 'bitbucket address' => [self::BITBUCKET_REPOSITORY_URL];
    }

    /**
     * @test
     * @dataProvider invalidRepositoryUrlDataProvider
     */
    public function invalidRepositoryUrlshouldThrowException(string $repositoryUrl): void
    {
        self::expectException(InvalidArgumentException::class);

        RepositoryDetail::fromRepositoryUrl($repositoryUrl);
    }

    /**
     * @psalm-return Generator<string, string[]>
     */
    public function invalidRepositoryUrlDataProvider(): Generator
    {
        yield 'empty string' => [''];
        yield 'not an url' => ['not an url string'];
        yield 'incomplete github url - only host' => ['https://github.com'];
        yield 'incomplete github url - only host and user' => ['https://github.com/user'];
        yield 'incomplete bitbucket url - only hosy' => ['https://bitbucket.org'];
        yield 'incomplete bitbucket url - only hosy and user' => ['https://bitbucket.org/user'];
    }

    /**
     * @test
     * @dataProvider unsupportedGitHostingServiceRepositoryUrlDataProvider
     */
    public function unsupportedGitHostingServiceShouldThrowException(string $repositoryUrl): void
    {
        self::expectException(InvalidArgumentException::class);

        RepositoryDetail::fromRepositoryUrl($repositoryUrl);
    }

    /**
     * @psalm-return Generator<string, string[]>
     */
    public function unsupportedGitHostingServiceRepositoryUrlDataProvider(): Generator
    {
        yield 'unsupported git hosting service - custom domain' => ['https://othergisthostingservice.org/user/reponame'];
        yield 'unsupported git hosting service - gitlab.com' => ['https://gitlab.com/user/reponame'];
        yield 'unsupported git hosting service - sourceforge.net' => ['https://sourceforge.net/user/reponame'];
        yield 'unsupported git hosting service - codeberg.org' => ['https://codeberg.org/user/reponame'];
        yield 'unsupported git hosting service - repo.or.cz' => ['https://repo.or.cz/user/reponame'];
        yield 'unsupported git hosting service - rocketgit.com' => ['https://rocketgit.com/user/reponame'];
    }

    /**
     * @test
     */
    public function canDetectGithubHositingService(): void
    {
        $sut = RepositoryDetail::fromRepositoryUrl(self::GITHUB_REPOSITORY_URL);

        self::assertTrue($sut->isGitHub());
    }

    /**
     * @test
     */
    public function canDetectBitbucketHositingService(): void
    {
        $sut = RepositoryDetail::fromRepositoryUrl(self::BITBUCKET_REPOSITORY_URL);

        self::assertTrue($sut->isBitbucket());
    }

    /**
     * @test
     */
    public function shouldReturnTheRepositoryOwner(): void
    {
        $sut = RepositoryDetail::fromRepositoryUrl('https://github.com/turing/gameoflife');

        self::assertEquals('turing', $sut->repositoryOwner());
    }

    /**
     * @test
     */
    public function shouldReturnTheRepositoryName(): void
    {
        $sut = RepositoryDetail::fromRepositoryUrl('https://github.com/turing/gameoflife');

        self::assertEquals('gameoflife', $sut->repositoryName());
    }

    /**
     * @test
     */
    public function shouldReturnTheRepositoryUrl(): void
    {
        $sut = RepositoryDetail::fromRepositoryUrl('https://github.com/turing/gameoflife');

        self::assertEquals('https://github.com/turing/gameoflife', $sut->repositoryUrl());
    }
}
