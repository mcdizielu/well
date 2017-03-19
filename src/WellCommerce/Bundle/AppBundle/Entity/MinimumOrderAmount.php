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

namespace WellCommerce\Bundle\AppBundle\Entity;

/**
 * Class MinimumOrderAmount
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class MinimumOrderAmount
{
    protected $value    = 0.00;
    protected $currency = '';
    
    public function getValue(): float
    {
        return $this->value;
    }
    
    public function setValue(float $value)
    {
        $this->value = $value;
    }
    
    public function getCurrency(): string
    {
        return $this->currency;
    }
    
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }
}
