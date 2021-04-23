<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\PackagistBuilder;

use Badge\Tests\Support\DomainBuilder\Data\PackagistData;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/** @covers \Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder */
final class PackagistBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithVendorNameAndProjectName(): void
    {
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname');

        $builder->addBitbucketAsHostingServiceProvider();

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals('vendorname/projectname', $data->name());
        self::assertEquals(0, $data->dependents());
        self::assertEquals(0, $data->suggesters());
        self::assertEquals(0, $data->totals());
        self::assertEquals(0, $data->monthly());
        self::assertEquals(0, $data->daily());
    }

    /**
     * @test
     */
    public function itShouldAddSuggesters(): void
    {
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addSuggesters(100);

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals(100, $data->suggesters());
    }

    /**
     * @test
     */
    public function itShouldAddDependents(): void
    {
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addSuggesters(100)
            ->addDependents(150);

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals(150, $data->dependents());
    }

    /**
     * @test
     */
    public function itShouldAddTotalDownloads(): void
    {
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addSuggesters(100)
            ->addDependents(150)
            ->addTotalDownloads(200);

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals(200, $data->totals());
    }

    /**
     * @test
     */
    public function itShouldAddMonthlyDownloads(): void
    {
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addSuggesters(100)
            ->addDependents(150)
            ->addTotalDownloads(200)
            ->addMonthlyDownloads(300);

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals(300, $data->monthly());
    }

    /**
     * @test
     */
    public function itShouldAddDailyDownloads(): void
    {
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addSuggesters(100)
            ->addDependents(150)
            ->addTotalDownloads(200)
            ->addMonthlyDownloads(300)
            ->addDailyDownloads(400);

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals(400, $data->daily());
    }

    /**
     * @test
     */
    public function itShouldAddGithubAsHostingServiceProvider(): void
    {
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addSuggesters(100)
            ->addDependents(150)
            ->addTotalDownloads(200)
            ->addMonthlyDownloads(300)
            ->addDailyDownloads(400)
            ->addGithubAsHostingServiceProvider();

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals('https://github.com/vendorname/projectname', $data->repository());
    }

    /**
     * @test
     */
    public function itShouldAddBitbucketAsHostingServiceProvider(): void
    {
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addSuggesters(100)
            ->addDependents(150)
            ->addTotalDownloads(200)
            ->addMonthlyDownloads(300)
            ->addDailyDownloads(400)
            ->addBitbucketAsHostingServiceProvider();

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals('https://bitbucket.org/vendorname/projectname', $data->repository());
    }

    /**
     * @test
     */
    public function itShouldFailIfThereIsNotAnGitHostingServiceProvider(): void
    {
        $this->expectException(RuntimeException::class);

        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addSuggesters(100)
            ->addDependents(150)
            ->addTotalDownloads(200)
            ->addMonthlyDownloads(300)
            ->addDailyDownloads(400);

        /** @var PackagistData $data */
        $data = $builder->build(false);
    }

    /**
     * @test
     */
    public function itShouldAddTheDesiredHttpStatusCode(): void
    {
        /** @var PackagistBuilder $builder */
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider()
            ->addSuggesters(100)
            ->addDependents(150)
            ->addHttpStatusCode(403)
            ->addTotalDownloads(200)
            ->addMonthlyDownloads(300)
            ->addTotalDownloads(200)
            ->addHttpStatusCode(404);

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals(404, $data->httpStatusCode());
    }

    /**
     * @test
     */
    public function itHas200AsDefaultHttpStatusCode(): void
    {
        $builder = PackagistBuilder::withVendorAndProjectName('vendorname', 'projectname')
            ->addGithubAsHostingServiceProvider();

        /** @var PackagistData $data */
        $data = $builder->build(false);

        self::assertEquals(200, $data->httpStatusCode());
    }
}
