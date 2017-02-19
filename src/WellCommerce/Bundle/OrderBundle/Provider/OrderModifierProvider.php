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

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderModifier;

/**
 * Class OrderModifierProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderModifierProvider implements OrderModifierProviderInterface
{
    /**
     * @var Collection
     */
    private $defaultModifiers;
    
    /**
     * OrderModifierProvider constructor.
     *
     * @param Collection $defaultModifiers
     */
    public function __construct(Collection $defaultModifiers)
    {
        $this->defaultModifiers = $defaultModifiers;
    }
    
    public function getOrderModifier(Order $order, string $name): OrderModifier
    {
        if (false === $order->hasModifier($name)) {
            return $this->createOrderModifier($order, $name);
        }
        
        return $order->getModifier($name);
    }
    
    private function createOrderModifier(Order $order, string $name): OrderModifier
    {
        $modifier = $this->getDefaultOrderModifier($name);
        $modifier->setOrder($order);
        
        return $modifier;
    }
    
    private function getDefaultOrderModifier(string $name): OrderModifier
    {
        return $this->defaultModifiers->get($name);
    }
}
