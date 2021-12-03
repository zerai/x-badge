<?php

declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\Data;

/**
 * null
 * @codeCoverageIgnore
 */
final class PackagistData
{
    private string $name;

    private int $dependents;

    private int $suggesters;

    private int $totals;

    private int $monthly;

    private int $daily;

    private string $repository;

    /**
     * @return list<Version>
     */
    private array $versions;

    private int $httpStatusCode;

    /**
     * @param list<Version> $versions
     */
    public function __construct(
        string $name = 'irrelevant',
        int $dependents = 0,
        int $suggesters = 0,
        int $totals = 0,
        int $monthly = 0,
        int $daily = 0,
        string $repository = '',
        array $versions = [],
        int $httpStatusCode = 200
    ) {
        $this->name = $name;
        $this->dependents = $dependents;
        $this->suggesters = $suggesters;
        $this->totals = $totals;
        $this->monthly = $monthly;
        $this->daily = $daily;
        $this->repository = $repository;
        $this->versions = $versions;
        $this->httpStatusCode = $httpStatusCode;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function withName(string $name): self
    {
        return new self(
            $name,
            $this->dependents,
            $this->suggesters,
            $this->totals,
            $this->monthly,
            $this->daily,
            $this->repository,
            $this->versions,
            $this->httpStatusCode
        );
    }

    public function dependents(): int
    {
        return $this->dependents;
    }

    public function withDependents(int $dependents): self
    {
        return new self(
            $this->name,
            $dependents,
            $this->suggesters,
            $this->totals,
            $this->monthly,
            $this->daily,
            $this->repository,
            $this->versions,
            $this->httpStatusCode
        );
    }

    public function suggesters(): int
    {
        return $this->suggesters;
    }

    public function withSuggesters(int $suggesters): self
    {
        return new self(
            $this->name,
            $this->dependents,
            $suggesters,
            $this->totals,
            $this->monthly,
            $this->daily,
            $this->repository,
            $this->versions,
            $this->httpStatusCode
        );
    }

    public function totals(): int
    {
        return $this->totals;
    }

    public function withTotals(int $totals): self
    {
        return new self(
            $this->name,
            $this->dependents,
            $this->suggesters,
            $totals,
            $this->monthly,
            $this->daily,
            $this->repository,
            $this->versions,
            $this->httpStatusCode
        );
    }

    public function monthly(): int
    {
        return $this->monthly;
    }

    public function withMonthly(int $monthly): self
    {
        return new self(
            $this->name,
            $this->dependents,
            $this->suggesters,
            $this->totals,
            $monthly,
            $this->daily,
            $this->repository,
            $this->versions,
            $this->httpStatusCode
        );
    }

    public function daily(): int
    {
        return $this->daily;
    }

    public function withDaily(int $daily): self
    {
        return new self(
            $this->name,
            $this->dependents,
            $this->suggesters,
            $this->totals,
            $this->monthly,
            $daily,
            $this->repository,
            $this->versions,
            $this->httpStatusCode
        );
    }

    public function repository(): string
    {
        return $this->repository;
    }

    public function withRepository(string $repository): self
    {
        return new self(
            $this->name,
            $this->dependents,
            $this->suggesters,
            $this->totals,
            $this->monthly,
            $this->daily,
            $repository,
            $this->versions,
            $this->httpStatusCode
        );
    }

    /**
     * @return list<Version>
     */
    public function versions(): array
    {
        return $this->versions;
    }

    /**
     * @param list<Version> $versions
     */
    public function withVersions(array $versions): self
    {
        return new self(
            $this->name,
            $this->dependents,
            $this->suggesters,
            $this->totals,
            $this->monthly,
            $this->daily,
            $this->repository,
            $versions,
            $this->httpStatusCode
        );
    }

    public function httpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function withHttpStatusCode(int $httpStatusCode): self
    {
        return new self(
            $this->name,
            $this->dependents,
            $this->suggesters,
            $this->totals,
            $this->monthly,
            $this->daily,
            $this->repository,
            $this->versions,
            $httpStatusCode
        );
    }

    public static function fromArray(array $data): self
    {
        if (! isset($data['name']) || ! \is_string($data['name'])) {
            throw new \InvalidArgumentException('Error on "name": string expected');
        }

        if (! isset($data['dependents']) || ! \is_int($data['dependents'])) {
            throw new \InvalidArgumentException('Error on "dependents": int expected');
        }

        if (! isset($data['suggesters']) || ! \is_int($data['suggesters'])) {
            throw new \InvalidArgumentException('Error on "suggesters": int expected');
        }

        if (! isset($data['totals']) || ! \is_int($data['totals'])) {
            throw new \InvalidArgumentException('Error on "totals": int expected');
        }

        if (! isset($data['monthly']) || ! \is_int($data['monthly'])) {
            throw new \InvalidArgumentException('Error on "monthly": int expected');
        }

        if (! isset($data['daily']) || ! \is_int($data['daily'])) {
            throw new \InvalidArgumentException('Error on "daily": int expected');
        }

        if (! isset($data['repository']) || ! \is_string($data['repository'])) {
            throw new \InvalidArgumentException('Error on "repository": string expected');
        }

        if (! isset($data['versions']) || ! \is_array($data['versions'])) {
            throw new \InvalidArgumentException('Error on "versions": array expected');
        }

        if (! isset($data['httpStatusCode']) || ! \is_int($data['httpStatusCode'])) {
            throw new \InvalidArgumentException('Error on "httpStatusCode": int expected');
        }

        return new self(
            $data['name'],
            $data['dependents'],
            $data['suggesters'],
            $data['totals'],
            $data['monthly'],
            $data['daily'],
            $data['repository'],
            array_map(fn ($e) => Version::fromArray($e), $data['versions']),
            $data['httpStatusCode'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'dependents' => $this->dependents,
            'suggesters' => $this->suggesters,
            'totals' => $this->totals,
            'monthly' => $this->monthly,
            'daily' => $this->daily,
            'repository' => $this->repository,
            'versions' => array_map(fn (Version $e) => $e->toArray(), $this->versions),
            'httpStatusCode' => $this->httpStatusCode,
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

        if ($this->dependents !== $other->dependents) {
            return false;
        }

        if ($this->suggesters !== $other->suggesters) {
            return false;
        }

        if ($this->totals !== $other->totals) {
            return false;
        }

        if ($this->monthly !== $other->monthly) {
            return false;
        }

        if ($this->daily !== $other->daily) {
            return false;
        }

        if ($this->repository !== $other->repository) {
            return false;
        }

        if (\count($this->versions) !== \count($other->versions)) {
            return false;
        }

        foreach ($this->versions as $k => $v) {
            if (! $v->equals($other->versions[$k])) {
                return false;
            }
        }

        if ($this->httpStatusCode !== $other->httpStatusCode) {
            return false;
        }

        return true;
    }

    public function mockedJson(): string
    {
        $dynamicData = $this->getDinamicDataForMockedJson();
        $staticData = [
            'description' => 'Irrelevant description for package.',
            'time' => '2011-09-27T00:35:19+00:00',
            'type' => 'library',
            'github_open_issues' => '111',
            'language' => 'PHP',
            'github_stars' => '222',
            'github_watchers' => '333',
            'github_forks' => '444',
            'favers' => '666',
            'maintainers' => [
                [
                    'name' => 'anonymous',
                    'avatar_url' => 'https://www.gravatar.com/avatar/3168e28fdb38c81f59cce6c1d28bcf52?d=identicon',
                ],
            ],
        ];

        $data = $dynamicData + $staticData;

        return json_encode([
            'package' => $data,
        ]);
    }

    public function buildVersions(): array
    {
        if (\count($this->versions) === 0) {
            return [];
        }

        foreach ($this->versions as $key => $value) {
            $data = [
                'name' => $value->name(),
                'version' => $value->version(),
                'version_normalized' => $value->version(),
                // TODO dinamic time
                'time' => '2021-01-05T12:38:00+00:00',
                // TODO dinamic license
                'license' => [
                    'MIT',
                ],
                'source' => [
                    'type' => 'git',
                    'url' => 'https://github.com/badges/poser.git',
                    'reference' => 'b8854ddbd9b3cf2fb127c2541b78403a070ba333',
                ],
            ];

            $versions[$value->version()] = $data;
        }

        return $versions;
    }

    private function getDinamicDataForMockedJson(): array
    {
        $versions = $this->buildVersions();

        return [
            'name' => $this->name,
            'dependents' => $this->dependents,
            'suggesters' => $this->suggesters,
            'repository' => $this->repository,
            'downloads' => [
                'total' => $this->totals,
                'monthly' => $this->monthly,
                'daily' => $this->daily,
            ],
            'versions' => $versions,
        ];
    }
}
