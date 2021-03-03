<?php declare(strict_types=1);

use Badge\Tests\Discovery\VO\BadgeContext;
use Badge\Tests\Discovery\VO\Subject;
use Badge\Tests\Discovery\VO\SubjectValue;
use PHPUnit\Framework\TestCase;

final class DefaultBadgeTest extends TestCase
{
    public function canCreateADefaultBadge(): void
    {
        $sut = new BadgeContext(
            new Subject('-'),
            SubjectValue::fromArray([
                'text' => '-',
                'color' => '7A7A7A'
            ]

            )
        );
    }
}
