<?php

namespace WellCommerce\Bundle\ShipmentBundle\Processor;

use WellCommerce\Bundle\ShipmentBundle\Adapter\ShipmentAdapterInterface;
use WellCommerce\Component\Collections\ArrayCollection;

/**
 * Class ShipmentAdapterCollection
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShipmentAdapterCollection extends ArrayCollection
{
    public function add(ShipmentAdapterInterface $adapter)
    {
        $this->items[$adapter->getAlias()] = $adapter;
    }
    
    public function get($key): ShipmentAdapterInterface
    {
        return $this->items[$key];
    }
}
