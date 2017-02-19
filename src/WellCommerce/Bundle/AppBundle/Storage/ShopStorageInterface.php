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

namespace WellCommerce\Bundle\AppBundle\Storage;

use WellCommerce\Bundle\AppBundle\Entity\Shop;

/**
 * Interface ShopStorageInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ShopStorageInterface
{
    public function getCurrentShop(): Shop;
    
    public function getCurrentShopIdentifier(): int;
    
    public function setCurrentShop(Shop $shop);
    
    public function hasCurrentShop(): bool;
}
