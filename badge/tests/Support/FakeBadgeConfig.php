<?php declare(strict_types=1);

namespace Badge\Tests\Support;

final class FakeBadgeConfig
{
    public const SUBJECT = 'fake';

    public const SUBJECT_VALUE = 'fake';

    public const COLOR = '#000000';

    public const AS_CONSTRUCTOR_ARRAY = [
        'subject' => self::SUBJECT,
        'subject-value' => self::SUBJECT_VALUE,
        'color' => self::COLOR,
    ];
}
