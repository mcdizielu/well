<?php

namespace WellCommerce\Bundle\OrderBundle\Adapter;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\AbstractContainerAware;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\CoreBundle\Manager\ManagerInterface;
use WellCommerce\Bundle\OrderBundle\Entity\OrderStatus;
use WellCommerce\Bundle\OrderBundle\Entity\OrderStatusHistory;
use WellCommerce\Bundle\OrderBundle\Entity\ShipmentInterface;

/**
 * Class AbstractShipmentAdapter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractShipmentAdapter extends AbstractContainerAware implements ShipmentAdapterInterface
{
    public function addShipment(ShipmentInterface $shipment, array $formValues): Response
    {
        // TODO: Implement addShipment() method.
    }
    
    public function generateLabel(ShipmentInterface $shipment)
    {
        // TODO: Implement generateLabel() method.
    }
    
    public function getLabel(ShipmentInterface $shipment)
    {
        // TODO: Implement getLabel() method.
    }
    
    public function getLabels(string $date)
    {
        // TODO: Implement getLabels() method.
    }
    
    protected function getShipmentRepository(): RepositoryInterface
    {
        return $this->get('shipment.repository');
    }
    
    protected function createOrderStatusHistory(ShipmentInterface $shipment, array $formValues): OrderStatusHistory
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
