<?php

namespace WellCommerce\Bundle\OrderBundle\Entity;

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\AppBundle\Entity\Price;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class ShippingMethodCost
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShippingMethodCost implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    
    protected $rangeFrom = 0.00;
    protected $rangeTo   = 0.00;
    
    /**
     * @var Price
     */
    protected $cost;
    
    /**
     * @var ShippingMethod
     */
    protected $shippingMethod;
    
    public function __construct()
    {
        $this->cost = new Price();
    }
    
    public function getRangeFrom(): float
    {
        return $this->rangeFrom;
    }
    
    public function setRangeFrom(float $rangeFrom)
    {
        $this->rangeFrom = (float)$rangeFrom;
    }
    
    public function getRangeTo(): float
    {
        return $this->rangeTo;
    }
    
    public function setRangeTo(float $rangeTo)
    {
        $this->rangeTo = (float)$rangeTo;
    }
    
    public function getCost(): Price
    {
        return $this->cost;
    }
    
    public function setCost(Price $cost)
    {
        $this->cost = $cost;
    }
    
    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }
    
    public function setShippingMethod(ShippingMethod $shippingMethod = null)
    {
        $this->shippingMethod = $shippingMethod;
    }
}
