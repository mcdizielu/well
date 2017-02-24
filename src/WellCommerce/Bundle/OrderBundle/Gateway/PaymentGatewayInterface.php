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

namespace WellCommerce\Bundle\OrderBundle\Gateway;

use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\OrderBundle\Entity\Payment;

/**
 * Interface PaymentGatewayInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface PaymentGatewayInterface
{
    public function initializePayment(Payment $payment);
    
    public function executePayment(Payment $payment, Request $request);
    
    public function confirmPayment(Payment $payment, Request $request);
    
    public function cancelPayment(Payment $payment, Request $request);
    
    public function notifyPayment(Payment $payment, Request $request);
}
