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

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\OrderBundle\Context\OrderContext;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;
use WellCommerce\Bundle\OrderBundle\Provider\OrderModifierProviderInterface;
use WellCommerce\Bundle\OrderBundle\Provider\ShippingMethodOptionsProviderInterface;
use WellCommerce\Bundle\OrderBundle\Provider\ShippingMethodProviderInterface;

/**
 * Class ShippingMethodCartVisitor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ShippingMethodOrderVisitor implements OrderVisitorInterface
{
    /**
     * @var OrderModifierProviderInterface
     */
    private $modifierProvider;
    
    /**
     * @var
     */
    private $methodProvider;
    
    /**
     * @var Collection
     */
    private $optionsProviderCollection;
    
    /**
     * ShippingMethodOrderVisitor constructor.
     *
     * @param OrderModifierProviderInterface  $modifierProvider
     * @param ShippingMethodProviderInterface $methodProvider
     */
    public function __construct(
        OrderModifierProviderInterface $modifierProvider,
        ShippingMethodProviderInterface $methodProvider,
        Collection $optionsProviderCollection
    ) {
        $this->modifierProvider          = $modifierProvider;
        $this->methodProvider            = $methodProvider;
        $this->optionsProviderCollection = $optionsProviderCollection;
    }
    
    public function visitOrder(Order $order)
    {
        $costs = $this->getCostCollection($order);
        
        if (0 === $costs->count()) {
            $order->removeModifier('shipping_cost');
            $order->setShippingMethod(null);
            $order->setShippingMethodOption(null);
            
            return;
        }
        
        $cost     = $costs->first();
        $modifier = $this->modifierProvider->getOrderModifier($order, 'shipping_cost');
        
        $modifier->setCurrency($cost->getShippingMethod()->getCurrency()->getCode());
        $modifier->setGrossAmount($cost->getCost()->getGrossAmount());
        $modifier->setNetAmount($cost->getCost()->getNetAmount());
        $modifier->setTaxAmount($cost->getCost()->getTaxAmount());
        
        $order->setShippingMethod($cost->getShippingMethod());
        $this->setShippingMethodOption($order, $cost->getShippingMethod());
    }
    
    private function setShippingMethodOption(Order $order, ShippingMethod $shippingMethod)
    {
        $optionsProvider = $this->getOptionsProvider($shippingMethod);
        $selectedOption  = $order->getShippingMethodOption();
        
        if ($optionsProvider instanceof ShippingMethodOptionsProviderInterface) {
            $options       = $optionsProvider->getShippingOptions();
            $defaultOption = current(array_keys($options));
            
            if (!isset($options[$selectedOption])) {
                $order->setShippingMethodOption($defaultOption);
            }
        } else {
            $order->setShippingMethodOption(null);
        }
    }
    
    private function getCostCollection(Order $order): Collection
    {
        if ($order->getShippingMethod() instanceof ShippingMethod) {
            $costs = $this->getCurrentShippingMethodCostsCollection($order);
            if ($costs->count() > 0) {
                return $costs;
            }
        }
        
        return $this->getShippingCostCollection($order);
    }
    
    private function getShippingCostCollection(Order $order): Collection
    {
        return $this->methodProvider->getCosts(new OrderContext($order));
    }
    
    private function getCurrentShippingMethodCostsCollection(Order $order): Collection
    {
        return $this->methodProvider->getShippingMethodCosts($order->getShippingMethod(), new OrderContext($order));
    }
    
    private function getOptionsProvider(ShippingMethod $method)
    {
        $provider = $method->getOptionsProvider();
        
        if ($this->optionsProviderCollection->containsKey($provider)) {
            return $this->optionsProviderCollection->get($provider);
        }
        
        return null;
    }
}
