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

namespace WellCommerce\Component\Layout\Configurator;

use WellCommerce\Component\Collections\ArrayCollection;

/**
 * Class LayoutBoxConfiguratorCollection
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LayoutBoxConfiguratorCollection extends ArrayCollection
{
    public function add(LayoutBoxConfiguratorInterface $configurator)
    {
        $this->items[$configurator->getType()] = $configurator;
    }
    
    public function get($key): LayoutBoxConfiguratorInterface
    {
        return $this->items[$key];
    }
}
