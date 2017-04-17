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

use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;
use WellCommerce\Bundle\OrderBundle\Entity\Invoice;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class InvoiceManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class InvoiceManager extends AbstractManager
{
    public function prepareInvoiceForOrder(Order $order): Invoice
    {
        /** @var Invoice $invoice */
        $invoice = $this->initResource();
        $invoice->setCurrency($order->getCurrency());
        $invoice->setAmountDue(0.00);
        $invoice->setAmountPaid($order->getSummary()->getGrossAmount());
        $invoice->setPaid(true);
        $invoice->setBillingAddress(clone $order->getBillingAddress());
        $invoice->setOrder($order);
        $invoice->setShop($order->getShop());
        $invoice->setShippingMethodName($order->getShippingMethod()->translate()->getName());
        $invoice->setPaymentMethodName($order->getPaymentMethod()->translate()->getName());
        $invoice->setSignature($this->getSecurityHelper()->getAuthenticatedAdmin()->getFullName());
        
        return $invoice;
    }
}
