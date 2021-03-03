<?php declare(strict_types=1);


/**
 *  DOMAIN LAYER
 */

namespace Badge\Application\Domain\Model {

    interface BadgeInterface
    {
    }

    interface ImageBadgeInterface extends BadgeInterface
    {
    }

    final class ImageBadge implements ImageBadgeInterface
    {
    }

    final class BadgeFactory
    {
        public static function createComposerLockBadge(): BadgeInterface
        {
        }
    }
}

namespace Badge\Application\Domain\Model\VO {


}


/**
 *  APPLICATION LAYER
 */

namespace Badge\Application {

use Badge\Application\Domain\Model\BadgeInterface;
use Badge\Application\Domain\Model\ImageBadge;

    interface BadgeUsecaseInterface
    {
        public function createBadge(): BadgeInterface;
    }

    final class BadgeComposerLockUsecase implements BadgeUsecaseInterface
    {
        public function createBadge(): BadgeInterface
        {
            // factory create a (RenderableBadge|AnemicBadge|BaseBadge)

            $badge = BadgeFactory::createComposerLockBadge();

            // try apply context to Badge

            // if ok return Badge

            // or apply ErrorContext to Badge

            // or mark as default badge.

            return new ImageBadge();
        }
    }
}
