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
namespace WellCommerce\Bundle\OrderBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WellCommerce\Bundle\CoreBundle\Doctrine\Event\EntityEvent;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\OrderBundle\Entity\OrderStatus;
use WellCommerce\Bundle\OrderBundle\Entity\OrderStatusGroup;

/**
 * Class OrderStatusSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderStatusSubscriber implements EventSubscriberInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    /**
     * OrderStatusSubscriber constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'order_status.post_init' => ['onOrderStatusPostInit', 0],
        ];
    }
    
    public function onOrderStatusPostInit(EntityEvent $event)
    {
        $orderStatus = $event->getEntity();
        if ($orderStatus instanceof OrderStatus) {
            $orderStatus->setOrderStatusGroup($this->getDefaultOrderStatusGroup());
        }
    }
    
    private function getDefaultOrderStatusGroup(): OrderStatusGroup
    {
        return $this->repository->findOneBy([]);
    }
}
