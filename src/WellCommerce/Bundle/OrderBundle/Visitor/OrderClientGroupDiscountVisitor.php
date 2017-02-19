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

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\ClientGroup;
use WellCommerce\Bundle\AppBundle\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\AbstractContainerAware;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Provider\OrderModifierProviderInterface;

/**
 * Class OrderClientDiscountVisitor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderClientGroupDiscountVisitor extends AbstractContainerAware implements OrderVisitorInterface
{
    /**
     * @var OrderModifierProviderInterface
     */
    private $orderModifierProvider;
    
    /**
     * @var CurrencyHelperInterface
     */
    private $currencyHelper;
    
    /**
     * OrderClientDiscountVisitor constructor.
     *
     * @param OrderModifierProviderInterface $orderModifierProvider
     * @param CurrencyHelperInterface        $currencyHelper
     */
    public function __construct(OrderModifierProviderInterface $orderModifierProvider, CurrencyHelperInterface $currencyHelper)
    {
        $this->orderModifierProvider = $orderModifierProvider;
        $this->currencyHelper        = $currencyHelper;
    }
    
    public function visitOrder(Order $order)
    {
        $client = $order->getClient();
        
        if ($client instanceof Client && null === $order->getCoupon()) {
            $clientGroup   = $client->getClientGroup();
            $modifierValue = $this->getDiscountForClientGroup($clientGroup);
            
            if ($modifierValue > 0) {
                $modifier = $this->orderModifierProvider->getOrderModifier($order, 'client_group_discount');
                $modifier->setCurrency($order->getCurrency());
                $modifier->setGrossAmount($order->getProductTotal()->getGrossPrice() * $modifierValue);
                $modifier->setNetAmount($order->getProductTotal()->getNetPrice() * $modifierValue);
                $modifier->setTaxAmount($order->getProductTotal()->getTaxAmount() * $modifierValue);
            }
        } else {
            $order->removeModifier('client_group_discount');
        }
    }
    
    private function getDiscountForClientGroup(ClientGroup $clientGroup = null): float
    {
        if (null !== $clientGroup) {
            return round((float)$clientGroup->getDiscount() / 100, 2);
        }
        
        return 0;
    }
}
