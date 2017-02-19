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
 * Class ShopStorage
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ShopStorage implements ShopStorageInterface
{
    /**
     * @var Shop
     */
    private $currentShop;
    
    public function getCurrentShopIdentifier(): int
    {
        if ($this->hasCurrentShop()) {
            return $this->getCurrentShop()->getId();
        }
        
        return 0;
    }
    
    public function hasCurrentShop(): bool
    {
        return $this->currentShop instanceof Shop;
    }
    
    public function getCurrentShop(): Shop
    {
        return $this->currentShop;
    }
    
    public function setCurrentShop(Shop $shop)
    {
        $this->currentShop = $shop;
    }
}
