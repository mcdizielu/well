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
namespace WellCommerce\Bundle\OrderBundle\Entity;

use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class OrderStatus
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderStatus implements EntityInterface
{
    use Identifiable;
    use Enableable;
    use Timestampable;
    use Blameable;
    use Translatable;
    
    protected $colour = '#fff';
    
    /**
     * @var OrderStatusGroup
     */
    protected $orderStatusGroup;
    
    public function getOrderStatusGroup(): OrderStatusGroup
    {
        return $this->orderStatusGroup;
    }
    
    public function setOrderStatusGroup(OrderStatusGroup $orderStatusGroup)
    {
        $this->orderStatusGroup = $orderStatusGroup;
    }
    
    public function getColour(): string
    {
        return $this->colour;
    }
    
    public function setColour(string $colour)
    {
        $this->colour = $colour;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): OrderStatusTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
