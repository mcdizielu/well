<?php

namespace WellCommerce\Bundle\InvoiceBundle\Processor;

use WellCommerce\Component\Collections\ArrayCollection;

/**
 * Class InvoiceProcessorCollection
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class InvoiceProcessorCollection extends ArrayCollection
{
    public function add(InvoiceProcessorInterface $processor)
    {
        $this->items[$processor->getAlias()] = $processor;
    }
    
    public function get($key): InvoiceProcessorInterface
    {
        return $this->items[$key];
    }
}
