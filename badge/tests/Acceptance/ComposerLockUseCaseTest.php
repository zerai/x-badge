<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

/** @covers \Badge\Application\Usecase\ComposerLockBadgeGenerator */
final class ComposerLockUseCaseTest extends AcceptanceTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function canCreateABadge(): void
    {
        self::markTestIncomplete('Configurare Fake Servers Github|bitbucket...');
        $result = $this->application->createComposerLockBadge('phpunit/phpunit');
    }
}
