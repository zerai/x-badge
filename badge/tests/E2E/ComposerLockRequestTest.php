<?php declare(strict_types=1);

namespace Badge\Tests\E2E;

use Badge\Core\BadgeApplicationInterface;
use Badge\Infrastructure\ProductionServiceContainer;
use Generator;
use PHPUnit\Framework\TestCase;

class ComposerLockRequestTest extends TestCase
{
    private BadgeApplicationInterface $application;

    protected function setUp(): void
    {
        $container = new ProductionServiceContainer();
        $this->application = $container->application();
    }

    /**
     * @test
     * @dataProvider packageWithCommittedComposerLockFile
     */
    public function createComposerLockBadge(string $packageName): void
    {
        self::markTestIncomplete('Completare last-mile code.... controller e framework stuff.');
        $badgeImage = $this->application->createComposerLockBadge($packageName);

        self::assertStringContainsString('committed', $badgeImage->getFileName());
    }

    /**
     * @psalm-return Generator<int, array{0: string}, mixed, void>
     */
    public function packageWithCommittedComposerLockFile(): Generator
    {
        yield ['phpunit/phpunit'];
        yield ['phpunit/php-code-coverage'];
    }

    /**
     * @psalm-return Generator<int, array{0: string}, mixed, void>
     */
    public function packageWithUncommittedComposerLockFile(): Generator
    {
        yield ['phpunit/phpunit'];
        yield ['phpunit/php-code-coverage'];
    }

    /**
     * @psalm-return Generator<int, array{0: string}, mixed, void>
     */
    public function packageWithCommittedGitAttributesFile(): Generator
    {
        yield ['phpunit/phpunit'];
        yield ['phpunit/php-code-coverage'];
    }
}
