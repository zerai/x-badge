<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use PHPUnit\Framework\TestCase;

final class PackagistContextValueReaderReadFromPackagistApiServerTest extends TestCase
{
    protected function setUp(): void
    {
        self::markTestIncomplete('Implementare un fake server per I/O testing.');
    }

    /**
     * @test
     */
    public function shouldReadTheSuggestersValueFromApiServer(): void
    {
    }
}
