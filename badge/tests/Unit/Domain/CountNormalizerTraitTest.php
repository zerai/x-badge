<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain;

use Badge\Core\Domain\Model\ContextValue\Common\CountNormalizerTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Core\Domain\Model\ContextValue\Common\CountNormalizerTrait */
final class CountNormalizerTraitTest extends TestCase
{
    /**
     * @var CountNormalizerTrait & MockObject
     */
    private $countNormalizerTrait;

    protected function setUp(): void
    {
        $this->countNormalizerTrait = $this->getMockForTrait(CountNormalizerTrait::class);
    }

    /**
     * @test
     * @dataProvider validInputNumberToConvert
     */
    public function shouldNormalizeNumberAsHumanReadableString(int $input, string $output): void
    {
        $result = $this->countNormalizerTrait->normalize($input);

        $this->assertEquals($output, $result);
    }

    /**
     * @return (int|string)[][]
     *
     * @psalm-return array{0: array{0: int, 1: string}}
     */
    public static function validInputNumberToConvert(): array
    {
        return [
            [0,             '1'],
            [1,             '1'],
            [16,            '16'],
            [199,           '199'],
            [1012,          '1.01 k'],
            [1212,          '1.21 k'],
            [1999,          '2 k'],
            [1003000,       '1 M'],
            [9001003000,    '9 G'],
            [9001003000000, '9 T'],
        ];
    }
}
