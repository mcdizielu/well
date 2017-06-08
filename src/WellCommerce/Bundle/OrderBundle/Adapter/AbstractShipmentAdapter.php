<?php

namespace WellCommerce\Bundle\OrderBundle\Adapter;

use WellCommerce\Bundle\CoreBundle\DependencyInjection\AbstractContainerAware;
use WellCommerce\Bundle\CoreBundle\Manager\ManagerInterface;
use WellCommerce\Bundle\OrderBundle\Entity\OrderStatus;
use WellCommerce\Bundle\OrderBundle\Entity\OrderStatusHistory;
use WellCommerce\Bundle\OrderBundle\Entity\Shipment;
use WellCommerce\Bundle\OrderBundle\Manager\ShipmentManager;

/**
 * Class AbstractShipmentAdapter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractShipmentAdapter extends AbstractContainerAware implements ShipmentAdapterInterface
{
    protected $manager;
    
    public function __construct(ShipmentManager $manager)
    {
        $this->manager = $manager;
    }
    
    protected function createOrderStatusHistory(Shipment $shipment, array $formValues): OrderStatusHistory
    {
        /** @var ManagerInterface $manager */
        $manager       = $this->get('order_status_history.manager');
        $order         = $shipment->getOrder();
        $notify        = (bool)$formValues['required_data']['notify'];
        $comment       = $formValues['required_data']['comment'];
        $orderStatusId = $formValues['required_data']['orderStatus'];
        
        /** @var OrderStatus $orderStatus */
        $orderStatus = $this->get('order_status.repository')->find($orderStatusId);
        
        if (!$orderStatus instanceof OrderStatus) {
            throw new \Exception('Wrong order status given.');
        }
        
        $order->setCurrentStatus($orderStatus);
        
        /** @var OrderStatusHistory $orderStatusHistory */
        $orderStatusHistory = $this->get('order_status_history.factory')->create();
        $orderStatusHistory->setNotify($notify);
        $orderStatusHistory->setComment($comment);
        $orderStatusHistory->setOrder($order);
        $orderStatusHistory->setOrderStatus($order->getCurrentStatus());
        
        $manager->createResource($orderStatusHistory);
        
        return $orderStatusHistory;
    }
}
