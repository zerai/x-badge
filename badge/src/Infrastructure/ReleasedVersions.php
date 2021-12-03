<?php declare(strict_types=1);

namespace Badge\Infrastructure;

use Packagist\Api\Result\Package\Version;

final class ReleasedVersions
{
    private static string $modifierRegex = '[._-]?(?:(stable|beta|b|RC|alpha|a|patch|pl|p)(?:[.-]?(\d+))?)?([.-]?dev)?';

    private ?string $license = null;

    private ?string $latestStableVersion = null;

    private ?string $latestUnstableVersion = null;

    private ?string $latestStableVersionNormalized = null;

    private ?string $latestUnstableVersionNormalized = null;

    /**
     * @var array<Version[]>
     */
    private $versions = [];

    /**
     * @param array<Version[]> $versions
     */
    public function __construct(array $versions)
    {
        $this->versions = $versions;
        $this->calculateLatestVersions();
    }

    /**
     * @param array<Version[]> $versions
     */
    public static function fromApiData(array $versions): self
    {
        return new self($versions);
    }

    public function stableVersion(): ?string
    {
        return $this->latestStableVersion;
    }

    public function unstableVersion(): ?string
    {
        return $this->latestUnstableVersion;
    }

    public function license(): ?string
    {
        return $this->license;
    }

    private function getLatestStableVersionNormalized(): ?string
    {
        return $this->latestStableVersionNormalized;
    }

    private function getLatestUnstableVersionNormalized(): ?string
    {
        return $this->latestUnstableVersionNormalized;
    }

    private function calculateLatestVersions(): self
    {
        $versions = $this->versions;

        /** @psalm-suppress InvalidArgument */
        usort($versions, [$this, 'comparator']);

        /** @var Version $version */
        foreach ($versions as $name => $version) {
            $currentVersionName = $version->getVersion();
            $versionNormalized = $version->getVersionNormalized();

            $aliases = $this->getBranchAliases($version);
            if ($aliases !== null && \array_key_exists($currentVersionName, $aliases)) {
                $currentVersionName = $aliases[$currentVersionName];
            }

            $functionName = VersionStability::fromString($currentVersionName)->detect();

            /**
             * START CODICE AGGIUNTO:
             *
             * Fix exception all'inizio del loop, la funzione \version_compare()
             * vuole 2 argomenti di tipo string version,
             * ma al primo ciclo il secondo argomento $this->{'getLatest' . $functionName . 'VersionNormalized'}()
             * e sempre null.
             */
            if (null === $this->{'getLatest' . $functionName . 'VersionNormalized'}()) {
                $this->{'setLatest' . $functionName . 'Version'}($currentVersionName);
                $this->{'setLatest' . $functionName . 'VersionNormalized'}($versionNormalized);
            }
            /**
             * END CODICE AGGIUNTO:
             */

            if (version_compare($versionNormalized, $this->{'getLatest' . $functionName . 'VersionNormalized'}()) > 0) {
                $this->{'setLatest' . $functionName . 'Version'}($currentVersionName);
                $this->{'setLatest' . $functionName . 'VersionNormalized'}($versionNormalized);
                /** @var string|string[] $license */
                $license = $version->getLicense();

                $this->setLicense($this->normalizeLicense($license));
            }
        }

        return $this;
    }

    /**
     * Get all the branch aliases.
     *
     * @return array<string, string>|null
     */
    private function getBranchAliases(Version $version): ?array
    {
        $extra = $version->getExtra();
        if ($extra !== null && isset($extra['branch-alias']) && \is_array($extra['branch-alias'])) {
            return $extra['branch-alias'];
        }

        return null;
    }

    private function setLicense(string $license): void
    {
        $this->license = $license;
    }

    /**
     * @param string|string[] $licenseData
     */
    private function normalizeLicense($licenseData): string
    {
        if (! \is_array($licenseData)) {
            return $licenseData;
        }

        if (\count($licenseData) > 0) {
            return implode(',', $licenseData);
        }

        return '';
    }

    private function comparator(Version $version1, Version $version2): int
    {
        return $version1->getTime() <=> $version2->getTime();
    }

    private function setLatestStableVersion(string $version): void
    {
        $this->latestStableVersion = $version;
    }

    private function setLatestUnstableVersion(string $version): void
    {
        $this->latestUnstableVersion = $version;
    }

    private function setLatestStableVersionNormalized(string $latestStableVersionNormalized): void
    {
        $this->latestStableVersionNormalized = $latestStableVersionNormalized;
    }

    private function setLatestUnstableVersionNormalized(string $latestUnstableVersionNormalized): void
    {
        $this->latestUnstableVersionNormalized = $latestUnstableVersionNormalized;
    }
}
