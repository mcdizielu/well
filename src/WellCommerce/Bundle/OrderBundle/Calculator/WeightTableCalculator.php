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

namespace WellCommerce\Bundle\OrderBundle\Calculator;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethodCost;

/**
 * Class WeightTableCalculator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WeightTableCalculator implements ShippingCalculatorInterface
{
    public function calculate(ShippingMethod $shippingMethod, ShippingSubjectInterface $subject): Collection
    {
        $ranges      = $shippingMethod->getCosts();
        $totalWeight = $subject->getWeight();
        
        return $ranges->filter(function (ShippingMethodCost $cost) use ($totalWeight) {
            return ($cost->getRangeFrom() <= $totalWeight && $cost->getRangeTo() >= $totalWeight);
        });
    }
    
    public function getAlias(): string
    {
        return 'weight_table';
    }
}
