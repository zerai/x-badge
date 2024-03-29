<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\License;

class LicenseProducer implements ContextProducer
{
    private LicenseReader $licenseReader;

    public function __construct(LicenseReader $licenseReader)
    {
        $this->licenseReader = $licenseReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return License::fromString($this->licenseReader->readLicense($packageName));
    }
}
