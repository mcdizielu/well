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

/**
 * Class ShopAwareTrait
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
trait ShopAwareTrait
{
    /**
     * @var Shop
     */
    protected $shop;
    
    public function getShop(): Shop
    {
        return $this->shop;
    }
    
    public function setShop(Shop $shop)
    {
        $this->shop = $shop;
    }
}
