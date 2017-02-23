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

/**
 * Interface ShippingCalculatorInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ShippingCalculatorInterface
{
    public function getAlias(): string;
    
    /**
     * Returns the shipping costs collection for given amount
     *
     * @param ShippingMethod           $method
     * @param ShippingSubjectInterface $subject
     *
     * @return Collection
     */
    public function calculate(ShippingMethod $method, ShippingSubjectInterface $subject): Collection;
}

