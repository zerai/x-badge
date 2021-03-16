<?php declare(strict_types=1);

namespace Badge\Tests\Unit\BadgeContext;

use Badge\Application\Domain\Model\BadgeContext\SubjectValue;
use PHPUnit\Framework\TestCase;

final class SubjectValueTest extends TestCase
{
    /**
     * @test
     */
    public function canBeCompared(): void
    {
        $first = SubjectValue::fromArray(
            [
                'text' => 'irrelevant',
                'color' => '#000000',
            ]
        );

        $second = SubjectValue::fromArray(
            [
                'text' => 'fake',
                'color' => '#FFFFFF',
            ]
        );

        $copyOfFirst = SubjectValue::fromArray(
            [
                'text' => 'irrelevant',
                'color' => '#000000',
            ]
        );

        self::assertTrue($first->equals($copyOfFirst));
        self::assertFalse($first->equals($second));
        self::assertFalse($second->equals($copyOfFirst));
    }
}
