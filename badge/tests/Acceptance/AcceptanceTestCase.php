<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Core\BadgeApplicationInterface;
use PHPUnit\Framework\TestCase;

abstract class AcceptanceTestCase extends TestCase
{
    /**
     * @var BadgeApplicationInterface
     */
    protected $application;

    protected function setUp(): void
    {
        $this->application = $this->createApplication();
    }

    protected function tearDown(): void
    {
        $this->application = null;
    }

    /**
     * Creates a Core.
     *
     * @return BadgeApplicationInterface A BadgeApplication instance
     */
    protected static function createApplication(): BadgeApplicationInterface
    {
        $container = new ServiceContainerForAcceptanceTesting();

        return $container->application();
    }
}
