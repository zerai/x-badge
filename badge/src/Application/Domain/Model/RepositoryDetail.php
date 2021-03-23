<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model;

use Webmozart\Assert\Assert;

final class RepositoryDetail
{
    private const GITHUB_SOURCE = 'github.com';

    private const BITBUCKET_SOURCE = 'bitbucket.org';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $this->validate($value);
    }

    public static function fromRepositoryUrl(string $repositoryUrl): self
    {
        return new self($repositoryUrl);
    }

    public function repositoryUrl(): string
    {
        return $this->value;
    }

    public function isGitHub(): bool
    {
        return \parse_url($this->value, \PHP_URL_HOST) === self::GITHUB_SOURCE;
    }

    public function isBitbucket(): bool
    {
        return \parse_url($this->value, \PHP_URL_HOST) === self::BITBUCKET_SOURCE;
    }

    public function repositoryOwner(): string
    {
        \preg_match('/(https)(:\/\/|@)([^\/:]+)[\/:]([^\/:]+)\/(.+)$/', $this->value, $matches);

        return $matches[4];
    }

    public function repositoryName(): string
    {
        \preg_match('/(https)(:\/\/|@)([^\/:]+)[\/:]([^\/:]+)\/(.+)$/', $this->value, $matches);

        return $matches[5];
    }

    private function validate(string $input): string
    {
        Assert::stringNotEmpty($input);
        Assert::true($this->isValidGitHostingServiceProvider($input));
        Assert::regex($input, '/(https)(:\/\/|@)([^\/:]+)[\/:]([^\/:]+)\/(.+)$/', $message = 'Invald repository address.');

        return $input;
    }

    private function isValidGitHostingServiceProvider(string $url): bool
    {
        if (str_contains($url, self::GITHUB_SOURCE)) {
            return true;
        }

        if (str_contains($url, self::BITBUCKET_SOURCE)) {
            return true;
        }

        return false;
    }
}
