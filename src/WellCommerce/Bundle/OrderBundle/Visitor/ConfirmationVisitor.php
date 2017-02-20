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

namespace WellCommerce\Bundle\OrderBundle\Visitor;

use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;
use WellCommerce\Bundle\OrderBundle\Entity\PaymentMethod;
use WellCommerce\Bundle\OrderBundle\Generator\OrderNumberGeneratorInterface;
use WellCommerce\Bundle\OrderBundle\Manager\PaymentManagerInterface;

/**
 * Class ConfirmationVisitor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ConfirmationVisitor implements OrderVisitorInterface
{
    /**
     * @var OrderNumberGeneratorInterface
     */
    private $orderNumberGenerator;
    
    /**
     * @var PaymentManagerInterface
     */
    private $paymentManager;
    
    /**
     * OrderConfirmationVisitor constructor.
     *
     * @param OrderNumberGeneratorInterface $orderNumberGenerator
     * @param PaymentManagerInterface       $paymentManager
     */
    public function __construct(OrderNumberGeneratorInterface $orderNumberGenerator, PaymentManagerInterface $paymentManager)
    {
        $this->orderNumberGenerator = $orderNumberGenerator;
        $this->paymentManager       = $paymentManager;
    }
    
    public function visitOrder(Order $order)
    {
        if ($order->isConfirmed()) {
            $this->setOrderNumber($order);
            $this->setInitialOrderStatus($order);
            $this->setInitialPayment($order);
            $this->lockProducts($order);
        }
    }
    
    private function setOrderNumber(Order $order)
    {
        if (null === $order->getNumber()) {
            $orderNumber = $this->orderNumberGenerator->generateOrderNumber($order);
            $order->setNumber($orderNumber);
        }
    }
    
    private function setInitialOrderStatus(Order $order)
    {
        if (null === $order->getCurrentStatus() && $order->getPaymentMethod() instanceof PaymentMethod) {
            $paymentMethod = $order->getPaymentMethod();
            $order->setCurrentStatus($paymentMethod->getPaymentPendingOrderStatus());
        }
    }
    
    private function setInitialPayment(Order $order)
    {
        $payments = $order->getPayments();
        if (0 === $payments->count() && $order->getPaymentMethod() instanceof PaymentMethod) {
            $payment = $this->paymentManager->createPaymentForOrder($order);
            $payments->add($payment);
        }
    }
    
    private function lockProducts(Order $order)
    {
        $order->getProducts()->map(function (OrderProduct $orderProduct) {
            if ($orderProduct->getProduct()->getTrackStock()) {
                $this->decrementStock($orderProduct);
            }
            
            $orderProduct->setLocked(true);
        });
    }
    
    private function decrementStock(OrderProduct $orderProduct)
    {
        if ($orderProduct->hasVariant()) {
            $currentStock = $orderProduct->getVariant()->getStock();
            $orderProduct->getVariant()->setStock($currentStock - $orderProduct->getQuantity());
        } else {
            $currentStock = $orderProduct->getProduct()->getStock();
            $orderProduct->getProduct()->setStock($currentStock - $orderProduct->getQuantity());
        }
    }
}
