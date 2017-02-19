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
 * Interface ShopCollectionAwareInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ShopCollectionAwareInterface
{
    public function getShops(): Collection;
    
    public function setShops(Collection $shops);
    
    public function addShop(Shop $shop);
}
