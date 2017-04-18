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

namespace WellCommerce\Bundle\InvoiceBundle\Manager;

use Carbon\Carbon;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;
use WellCommerce\Bundle\InvoiceBundle\Entity\Invoice;
use WellCommerce\Bundle\InvoiceBundle\Entity\InvoiceItem;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;

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
        $invoice->setProcessor($order->getShop()->getInvoiceProcessor());
        $invoice->setDueDate(Carbon::now()->addDays((int)$order->getShop()->getInvoiceMaturity()));
        
        $this->prepareInvoiceItems($invoice, $order);
        
        return $invoice;
    }
    
    private function prepareInvoiceItems(Invoice $invoice, Order $order)
    {
        $order->getProducts()->map(function (OrderProduct $orderProduct) use ($invoice) {
            $invoiceItem = $this->createInvoiceItem($orderProduct);
            $invoiceItem->setInvoice($invoice);
            $invoice->getItems()->add($invoiceItem);
            $this->getEntityManager()->persist($invoiceItem);
        });
    }
    
    private function createInvoiceItem(OrderProduct $orderProduct): InvoiceItem
    {
        $invoiceItem = new InvoiceItem();
        $invoiceItem->setCurrency($orderProduct->getSellPrice()->getCurrency());
        $invoiceItem->setQuantity($orderProduct->getQuantity());
        $invoiceItem->setTaxRate($orderProduct->getSellPrice()->getTaxRate());
        $invoiceItem->setTaxAmount($orderProduct->getSellPrice()->getTaxAmount());
        $invoiceItem->setGrossAmount($orderProduct->getSellPrice()->getGrossAmount());
        $invoiceItem->setNetAmount($orderProduct->getSellPrice()->getNetAmount());
        $invoiceItem->setDescription($orderProduct->getProduct()->translate()->getName());
        
        return $invoiceItem;
    }
}
