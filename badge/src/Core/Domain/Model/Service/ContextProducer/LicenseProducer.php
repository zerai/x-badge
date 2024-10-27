<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\License;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingLicense;

class LicenseProducer implements ContextProducer
{
    private ForReadingLicense $licenseReader;

    public function __construct(ForReadingLicense $licenseReader)
    {
        $this->licenseReader = $licenseReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return License::fromString($this->licenseReader->readLicense($packageName));
    }
}
