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
use WellCommerce\Bundle\OrderBundle\Calculator\ShippingSubjectInterface;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;

/**
 * Interface ShippingMethodProviderInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ShippingMethodProviderInterface
{
    public function getCosts(ShippingSubjectInterface $subject): Collection;
    
    public function getShippingMethodCosts(ShippingMethod $method, ShippingSubjectInterface $subject): Collection;
}
