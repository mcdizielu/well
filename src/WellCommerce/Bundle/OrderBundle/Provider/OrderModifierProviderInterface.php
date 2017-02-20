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

namespace WellCommerce\Bundle\OrderBundle\Provider;

use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderModifier;

/**
 * Interface OrderModifierProviderInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface OrderModifierProviderInterface
{
    public function getOrderModifier(Order $order, string $name): OrderModifier;
}
