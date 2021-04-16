<?php declare(strict_types=1);

namespace Badge\Infrastructure;

use InvalidArgumentException;

final class VersionStability
{
    private const MODIFIER_REGEX = '[._-]?(?:(stable|beta|b|RC|alpha|a|patch|pl|p)(?:[.-]?(\d+))?)?([.-]?dev)?';

    private const STABLE = 'Stable';
    private const UNSTABLE = 'Unstable';

    private string $version;

    private function __construct(string $version)
    {
        $this->version = $this->validate($version);
    }

    public static function fromString(string $version): self
    {
        return new self($version);
    }

    public function detect(): string
    {
        $version = \preg_replace('{#.+$}i', '', $this->version) ?? '';

        if (\substr($version, 0, 4) === 'dev-' || \substr($version, -4) === '-dev') {
            return 'dev';
        }

        \preg_match('{' . self::MODIFIER_REGEX . '$}i', \strtolower($version), $match);
        if (! empty($match[3])) {
            return 'dev';
        }

        if (! empty($match[1])) {
            if ($match[1] === 'beta' || $match[1] === 'b') {
                return 'beta';
            }
            if ($match[1] === 'alpha' || $match[1] === 'a') {
                return 'alpha';
            }
            if ($match[1] === 'rc') {
                return 'RC';
            }
        }

        return self::STABLE;
    }

    private function validate(string $input): string
    {
        if ($input === '') {
            throw new InvalidArgumentException('Error: empty version not allowed');
        }

        return $input;
    }
}
