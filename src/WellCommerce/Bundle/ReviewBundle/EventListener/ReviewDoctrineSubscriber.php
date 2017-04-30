<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 * 
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 * 
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\ReviewBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\ReviewBundle\Entity\ReviewRecommendation;

/**
 * Class ReviewDoctrineSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ReviewDoctrineSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'postPersist',
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->updateRatioAndLikes($args);
    }


    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->onProductDataBeforeSave($args);
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->onProductDataBeforeSave($args);
    }
    
    private function onProductDataBeforeSave(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        
        if ($entity instanceof Product) {
            if (null === $entity->getEnableReviews()) {
                $entity->setEnableReviews(true);
            }
        }
    }

    private function updateRatioAndLikes(LifecycleEventArgs $args)
    {
        $reviewRecommendation = $args->getObject();

        if ($reviewRecommendation instanceof ReviewRecommendation) {
            $review                         = $reviewRecommendation->getReview();
            $reviewRecommendationRepository = $args->getObjectManager()->getRepository(ReviewRecommendation::class);
            $reviewLikes                    = $reviewRecommendationRepository->findBy([
                'review' => $review,
                'liked'  => true,
            ]);

            $reviewUnlikes = $reviewRecommendationRepository->findBy([
                'review'  => $review,
                'unliked' => true,
            ]);

            if (count($reviewUnlikes) > 0) {
                $ratio = count($reviewLikes) / count($reviewUnlikes);
            } else {
                $ratio = 1;
            }

            $review->setRatio($ratio);
            $review->setLikes(count($reviewLikes));
        }
    }
}
