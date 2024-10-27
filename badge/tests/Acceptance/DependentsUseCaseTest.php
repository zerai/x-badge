<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Core\BadgeImage;
use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Core\Usecase\DependentsBadgeGenerator */
final class DependentsUseCaseTest extends AcceptanceTestCase
{
    use BadgeImageAssertionsTrait;

    private const COLOR = '007ec6';

    private const SUBJECT = 'dependents';

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        ApiMockServer::reset();
    }

    /**
     * @test
     */
    public function createADependentsBadgeForAPackage(): void
    {
        PackagistBuilder::withVendorAndProjectName('badges', 'poser')
            ->addBitbucketAsHostingServiceProvider()
            ->addDependents(555)
            ->build();

        $result = $this->application->createDependentsBadge('badges/poser');

        self::assertTrue(self::badgeImageHasColor(self::COLOR, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
        self::assertTrue(self::badgeImageHasSubjectValue('555', $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveA404HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('irrelevanvendor', 'unknown-package')
            ->addBitbucketAsHostingServiceProvider()
            ->addDependents(500)
            ->addHttpStatusCode(404)
            ->build();

        $result = $this->application->createDependentsBadge('irrelevanvendor/unknown-package');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveA500HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('irrelevanvendor', 'unknown-package')
            ->addBitbucketAsHostingServiceProvider()
            ->addDependents(500)
            ->addHttpStatusCode(500)
            ->build();

        $result = $this->application->createDependentsBadge('irrelevanvendor/unknown-package');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createADependentsBadgeForAPackageWithZeroDependents(): void
    {
        PackagistBuilder::withVendorAndProjectName('badges', 'poser')
            ->addBitbucketAsHostingServiceProvider()
            ->addDependents(0)
            ->build();

        $result = $this->application->createDependentsBadge('badges/poser');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
    }
}
