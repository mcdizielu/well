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

namespace WellCommerce\Bundle\SimilarProductBundle\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;

/**
 * Class SimilarProductDoctrineSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SimilarProductDoctrineSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
        ];
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
            if (null === $entity->getSimilarProducts()) {
                $entity->setSimilarProducts(new ArrayCollection());
            }
        }
    }
}
