<?php declare(strict_types=1);

namespace Badge\Infrastructure;

use Badge\Application\BadgeImage;
use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use PUGX\Poser\Poser;

final class PoserImageFactory implements ImageFactory
{
    public const DEFAULT_BADGE_SUBJECT = '-';

    public const DEFAULT_BADGE_SUBJECT_VALUE = '-';

    public const DEFAULT_BADGE_COLOR = '#7A7A7A';

    private const DEFAULT_STYLE = 'flat';

    private const DEFAULT_FORMAT = 'svg';

    private Poser $generator;

    public function __construct(Poser $generator)
    {
        $this->generator = $generator;
    }

    public function createImageFromContext(BadgeContext $badgeContext): Image
    {
        $renderingProperties = $badgeContext->renderingProperties();

        $poserImage = $this->generator->generate(
            $renderingProperties['subject'],
            $renderingProperties['subject-value'],
            \trim($renderingProperties['color'], '#'),
            self::DEFAULT_STYLE,
            self::DEFAULT_FORMAT
        );

        return BadgeImage::create($this->imageName($badgeContext), (string) $poserImage);
    }

    public function createImageForDefaultBadge(): Image
    {
        $poserImage = $this->generator->generate(
            self::DEFAULT_BADGE_SUBJECT,
            self::DEFAULT_BADGE_SUBJECT_VALUE,
            \trim(self::DEFAULT_BADGE_COLOR, '#'),
            self::DEFAULT_STYLE,
            self::DEFAULT_FORMAT
        );

        return BadgeImage::create($this->imageName(), (string) $poserImage);
    }

    private function imageName(BadgeContext $badgeContext = null): string
    {
        if ($badgeContext === null) {
            return 'default-badge' . '.' . self::DEFAULT_FORMAT;
        }

        $renderingProperties = $badgeContext->renderingProperties();

        return \sprintf(
            '%s-%s-%s.%s',
            \trim($renderingProperties['subject'], '.'),
            $this->cleanName($renderingProperties['subject-value']),
            \trim($renderingProperties['color'], '#'),
            self::DEFAULT_FORMAT
        );
    }

    private function cleanName(string $value): string
    {
        $value = \str_replace('.', '-', $value);

        return \str_replace(' ', '-', $value);
    }
}
