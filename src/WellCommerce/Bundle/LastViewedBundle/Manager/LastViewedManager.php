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

namespace WellCommerce\Bundle\LastViewedBundle\Manager;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;
use WellCommerce\Bundle\LastViewedBundle\Entity\LastViewed;

/**
 * Class LastViewedManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LastViewedManager extends AbstractManager
{
    public function updateLastViewedProduct(Product $product, Client $client = null, string $sessionId)
    {
        if ($client instanceof Client) {
            $lastViewed = $this->getCurrentClientProduct($client, $product);
            if (!$lastViewed instanceof LastViewed) {
                $lastViewed = $this->getCurrentSessionProduct($sessionId, $product);
            }
        } else {
            $lastViewed = $this->getCurrentSessionProduct($sessionId, $product);
        }
        
        if (!$lastViewed instanceof LastViewed) {
            /** @var LastViewed $lastViewed */
            $lastViewed = $this->initResource();
            $lastViewed->setClient($client);
            $lastViewed->setProduct($product);
            $lastViewed->setSessionId($sessionId);
            $this->createResource($lastViewed);
        } else {
            $lastViewed->incrementViewedCount();
            $this->updateResource($lastViewed);
        }
    }
    
    public function getLastViewedProducts(Client $client = null, string $sessionId, Product $excludeProduct): Collection
    {
        $criteria = new Criteria();
        
        if ($client instanceof Client) {
            $criteria->where($criteria->expr()->eq('client', $client));
        } else {
            $criteria->where($criteria->expr()->eq('sessionId', $sessionId));
        }
        
        if ($excludeProduct instanceof Product) {
            $criteria->andWhere($criteria->expr()->neq('product', $excludeProduct));
        }
        
        return $this->repository->matching($criteria);
    }
    
    public function refreshSessionLastViewed(Client $client, string $sessionId)
    {
        $criteria = new Criteria();
        $criteria->andWhere($criteria->expr()->eq('sessionId', $sessionId));
        $collection = $this->repository->matching($criteria);
        
        $collection->map(function (LastViewed $lastViewed) use ($client) {
            $lastViewed->setClient($client);
        });
        
        $this->getEntityManager()->flush();
    }
    
    private function getCurrentClientProduct(Client $client, Product $product)
    {
        return $this->getRepository()->findOneBy([
            'client'  => $client,
            'product' => $product,
        ]);
    }
    
    private function getCurrentSessionProduct(string $sessionId, Product $product)
    {
        return $this->getRepository()->findOneBy([
            'sessionId' => $sessionId,
            'product'   => $product,
        ]);
    }
}
