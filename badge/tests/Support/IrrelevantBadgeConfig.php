<?php declare(strict_types=1);

namespace Badge\Tests\Support;

final class IrrelevantBadgeConfig
{
    public const SUBJECT = 'irrelevant';

    public const SUBJECT_VALUE = 'irrelevant';

    public const COLOR = '7B7B7B';

    public const AS_CONSTRUCTOR_ARRAY = [
        'subject' => self::SUBJECT,
        'subject-value' => self::SUBJECT_VALUE,
        'color' => self::COLOR,
    ];
}
