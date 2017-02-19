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

namespace WellCommerce\Bundle\OrderBundle\Provider\Admin;

use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Interface OrderProviderInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface OrderProviderInterface
{
    public function setCurrentOrder(Order $order);
    
    public function getCurrentOrder(): Order;
    
    public function hasCurrentOrder(): bool;
}
