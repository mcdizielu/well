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

namespace WellCommerce\Bundle\AppBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Class ShopCollectionAwareTrait
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
trait ShopCollectionAwareTrait
{
    /**
     * @var Collection
     */
    protected $shops;
    
    public function getShops(): Collection
    {
        return $this->shops;
    }
    
    public function setShops(Collection $shops)
    {
        $this->shops = $shops;
    }
    
    public function addShop(Shop $shop)
    {
        $this->shops->add($shop);
    }
}
