<?php declare(strict_types=1);

use Badge\Tests\Support\PoserFactory;

use PHPUnit\Framework\TestCase;
use PUGX\Poser\Poser;

final class PoserLearningTest extends TestCase
{
    /**
     * @var Poser
     */
    private $poser;

    protected function setUp(): void
    {
        $this->poser = PoserFactory::createMinimal();
        parent::setUp();
    }

    /**
     * @test
     */
    public function canGenerateABadge(): void
    {
        $image = $this->poser->generate('license', 'MIT', '428F7E', 'flat');

        self::assertEquals('flat', $image->getStyle());

        self::assertNotEmpty($image->__toString());
    }

    /**
     * @test
     */
    public function canGenerateBadgeFromUri(): void
    {
        $image = $this->poser->generateFromURI('license-MIT-428F7E.svg?style=flat');

        self::assertEquals('flat', $image->getStyle());

        self::assertNotEmpty($image->__toString());
    }
}
