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
 * Class OrderProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderProvider implements OrderProviderInterface
{
    private $currentOrder;
    
    public function setCurrentOrder(Order $order)
    {
        $this->currentOrder = $order;
    }
    
    public function getCurrentOrder(): Order
    {
        return $this->currentOrder;
    }
    
    public function hasCurrentOrder(): bool
    {
        return $this->currentOrder instanceof Order;
    }
}
