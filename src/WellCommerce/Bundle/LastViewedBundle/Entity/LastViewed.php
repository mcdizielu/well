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

namespace WellCommerce\Bundle\LastViewedBundle\Entity;

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Photo
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LastViewed implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    
    protected $viewedCount = 1;
    protected $sessionId   = '';
    
    /**
     * @var null|Client
     */
    protected $client;
    
    /**
     * @var Product
     */
    protected $product;
    
    public function getViewedCount(): int
    {
        return $this->viewedCount;
    }
    
    public function setViewedCount(int $viewedCount)
    {
        $this->viewedCount = $viewedCount;
    }
    
    public function incrementViewedCount(int $increment = 1)
    {
        $this->viewedCount += $increment;
    }
    
    public function getSessionId(): string
    {
        return $this->sessionId;
    }
    
    public function setSessionId(string $sessionId)
    {
        $this->sessionId = $sessionId;
    }
    
    public function getClient()
    {
        return $this->client;
    }
    
    public function setClient(Client $client = null)
    {
        $this->client = $client;
    }
    
    public function getProduct(): Product
    {
        return $this->product;
    }
    
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
}
