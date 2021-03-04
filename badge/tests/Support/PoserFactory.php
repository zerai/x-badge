<?php

declare(strict_types=1);

namespace Badge\Tests\Support;

use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use PUGX\Poser\Render\SvgPlasticRender;

final class PoserFactory
{
    public static function createMinimal(): Poser
    {
        return new Poser(
            new SvgFlatRender()
        );
    }

    public static function createFull(): Poser
    {
        return new Poser([
            new SvgFlatRender(),
            new SvgFlatSquareRender(),
            new SvgPlasticRender(),
        ]);
    }
}
