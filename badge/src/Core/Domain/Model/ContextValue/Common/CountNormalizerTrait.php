<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\ContextValue\Common;

trait CountNormalizerTrait
{
    public function normalize(int $number, int $precision = 2): string
    {
        $number = max((float) $number, 1);
        $units = ['', ' k', ' M', ' G', ' T'];
        $pow = floor(($number ? log($number) : 0) / log(1000));
        $pow = min($pow, \count($units) - 1);
        $number /= 1000 ** $pow;

        /** @psalm-suppress all */
        return round($number, $precision) . $units[$pow];
    }
}
