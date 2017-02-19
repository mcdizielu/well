<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\OrderBundle\Calculator;

use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Interface OrderProductTotalCalculatorInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface OrderProductTotalCalculatorInterface
{
    public function getTotalQuantity(Order $order): int;
    
    public function getTotalWeight(Order $order): float;
    
    public function getTotalNetAmount(Order $order): float;
    
    public function getTotalGrossAmount(Order $order): float;
    
    public function getTotalTaxAmount(Order $order): float;
}