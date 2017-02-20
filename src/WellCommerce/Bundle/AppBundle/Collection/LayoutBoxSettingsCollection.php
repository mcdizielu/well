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

namespace WellCommerce\Bundle\AppBundle\Collection;

use WellCommerce\Component\Collections\ArrayCollection;

/**
 * Class LayoutBoxSettingsCollection
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LayoutBoxSettingsCollection extends ArrayCollection
{
    public function add($name, $value)
    {
        $this->items[$name] = $value;
    }
    
    public function getParam($name, $default = null)
    {
        return $this->has($name) ? $this->get($name) : $default;
    }
}
