<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Application\BadgeApplicationInterface;
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
     * Creates a Application.
     *
     * @return BadgeApplicationInterface A BadgeApplication instance
     */
    protected static function createApplication(): BadgeApplicationInterface
    {
        $container = new ServiceContainerForAcceptanceTesting();

        return $container->application();
    }
}
