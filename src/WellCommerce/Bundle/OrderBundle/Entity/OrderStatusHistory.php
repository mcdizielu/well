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
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Extra\OrderBundle\Entity\OrderStatusHistoryExtraTrait;

/**
 * Class OrderStatus
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderStatusHistory implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    use Blameable;
    use OrderAwareTrait;
    use OrderStatusHistoryExtraTrait;
    
    protected $comment = '';
    protected $notify  = false;
    
    /**
     * @var OrderStatus
     */
    protected $orderStatus;
    
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }
    
    public function setOrderStatus(OrderStatus $orderStatus = null)
    {
        $this->orderStatus = $orderStatus;
        $this->getOrder()->setCurrentStatus($orderStatus);
    }
    
    public function getComment(): string
    {
        return $this->comment;
    }
    
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }
    
    public function isNotify(): bool
    {
        return $this->notify;
    }
    
    public function setNotify(bool $notify)
    {
        $this->notify = $notify;
    }
}
