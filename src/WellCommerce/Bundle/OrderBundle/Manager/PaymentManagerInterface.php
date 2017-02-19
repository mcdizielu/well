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

namespace WellCommerce\Bundle\OrderBundle\Manager;

use WellCommerce\Bundle\CoreBundle\Manager\ManagerInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\Payment;
use WellCommerce\Bundle\OrderBundle\Processor\PaymentProcessorInterface;

/**
 * Interface PaymentManagerInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface PaymentManagerInterface extends ManagerInterface
{
    public function updatePaymentState(Payment $payment);
    
    public function createPaymentForOrder(Order $order): Payment;
    
    public function getPaymentProcessor(Order $order): PaymentProcessorInterface;
}
