<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Application;

use Badge\Application\BadgeImage;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\BadgeImage */
final class BadgeImageTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnTheImageFileName(): void
    {
        $image = BadgeImage::create('a-filename.svg', 'a-content');

        self::assertEquals('a-filename.svg', $image->getFileName());
    }

    /**
     * @test
     */
    public function shouldReturnTheImageContent(): void
    {
        $image = BadgeImage::create('a-filename.svg', 'a-content');

        self::assertEquals('a-content', $image->getContent());
        self::assertEquals('a-content', $image->__toString());
    }

    /**
     * @test
     * @dataProvider invalidImageDataProvider
     */
    public function invalidValuesInconstructorShouldThrowException(string $aFileName, string $aFileContent): void
    {
        self::expectException(InvalidArgumentException::class);

        BadgeImage::create($aFileName, $aFileContent);
    }

    /**
     * @return Generator<string, array<int, string>>
     */
    public function invalidImageDataProvider(): Generator
    {
        yield 'empty file name' => ['', 'a-content'];
        yield 'empty content' => ['a-filename.svg', ''];
        yield 'invalid extension in file name' => ['invalid-extension.txt', 'a-content'];
        yield 'invalid char (.) in file name' => ['invalid.extension.svg', 'a-content'];
        yield 'invalid char (#) in file name' => ['invalid#extension.svg', 'a-content'];
        yield 'invalid char (_) in file name' => ['invalid_extension.svg', 'a-content'];
    }
}
