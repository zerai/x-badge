<?php

declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\Data;

use DateTimeImmutable;

/**
 * null
 * @codeCoverageIgnore
 */
final class Version
{
    private string $name;

    private string $version;

    private string $version_normalized;

    private string $license;

    private DateTimeImmutable $time;

    public function __construct(
        ?string $name = 'irrelevant/irrelevant',
        ?string $version = 'v0.0.1',
        ?string $version_normalized = '0.0.1',
        ?string $license = 'MIT',
        ?DateTimeImmutable $time
    ) {
        $this->name = $name;
        $this->version = $version;
        $this->version_normalized = $version_normalized;
        $this->license = $license;
        $this->time = new DateTimeImmutable('now');
    }

    public function name(): string
    {
        return $this->name;
    }

    public function withName(string $name): self
    {
        return new self(
            $name,
            $this->version,
            $this->version_normalized,
            $this->license,
            $this->time
        );
    }

    public function version(): string
    {
        return $this->version;
    }

    public function withVersion(string $version): self
    {
        return new self(
            $this->name,
            $version,
            $this->version_normalized,
            $this->license,
            $this->time
        );
    }

    public function version_normalized(): string
    {
        return $this->version_normalized;
    }

    public function withVersion_normalized(string $version_normalized): self
    {
        return new self(
            $this->name,
            $this->version,
            $version_normalized,
            $this->license,
            $this->time
        );
    }

    public function license(): string
    {
        return $this->license;
    }

    public function withLicense(string $license): self
    {
        return new self(
            $this->name,
            $this->version,
            $this->version_normalized,
            $license,
            $this->time
        );
    }

    public function time(): DateTimeImmutable
    {
        return $this->time;
    }

    public function withTime(DateTimeImmutable $time): self
    {
        return new self(
            $this->name,
            $this->version,
            $this->version_normalized,
            $this->license,
            $time
        );
    }

    public static function fromArray(array $data): self
    {
        if (! isset($data['name']) || ! \is_string($data['name'])) {
            throw new \InvalidArgumentException('Error on "name": string expected');
        }

        if (! isset($data['version']) || ! \is_string($data['version'])) {
            throw new \InvalidArgumentException('Error on "version": string expected');
        }

        if (! isset($data['version_normalized']) || ! \is_string($data['version_normalized'])) {
            throw new \InvalidArgumentException('Error on "version_normalized": string expected');
        }

        if (! isset($data['license']) || ! \is_string($data['license'])) {
            throw new \InvalidArgumentException('Error on "license": string expected');
        }

        if (! isset($data['time']) || ! DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.uP', $data['time'], new \DateTimeZone('UTC'))
        ) {
            throw new \InvalidArgumentException('Error on "time", datetime string expected');
        }

        return new self(
            $data['name'],
            $data['version'],
            $data['version_normalized'],
            $data['license'],
            (function () use ($data) {
                $_x = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.uP', $data['time'], new \DateTimeZone('UTC'));

                if ($_x === false) {
                    throw new \UnexpectedValueException('Expected a date time string');
                }

                return $_x;
            })(),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
            'version_normalized' => $this->version_normalized,
            'license' => $this->license,
            'time' => $this->time->format('Y-m-d\TH:i:s.uP'),
        ];
    }

    public function equals(?self $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if ($this->name !== $other->name) {
            return false;
        }

        if ($this->version !== $other->version) {
            return false;
        }

        if ($this->version_normalized !== $other->version_normalized) {
            return false;
        }

        if ($this->license !== $other->license) {
            return false;
        }

        if (! ($this->time === $other->time)) {
            return false;
        }

        return true;
    }
}
