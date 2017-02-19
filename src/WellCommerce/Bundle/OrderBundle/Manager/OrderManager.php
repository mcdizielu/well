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

namespace WellCommerce\Bundle\OrderBundle\Manager;

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class OrderManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderManager extends AbstractManager implements OrderManagerInterface
{
    public function getOrder(string $sessionId, Client $client = null, Shop $shop, string $currency): Order
    {
        $order = $this->findOrder($sessionId, $client, $shop);
        
        if ($order instanceof Order) {
            if ($this->isOrderDirty($order, $currency, $client, $sessionId)) {
                $order->setCurrency($currency);
                $order->setClient($client);
                $order->setSessionId($sessionId);
                $this->updateResource($order);
            }
            
            return $order;
        }
        
        /** @var Order $order */
        $order = $this->initResource();
        $order->setCurrency($currency);
        $order->setShop($shop);
        $order->setClient($client);
        $order->setSessionId($sessionId);
        
        if ($client instanceof Client) {
            $order->setClientDetails($client->getClientDetails());
            $order->setContactDetails($client->getContactDetails());
            $order->setBillingAddress($client->getBillingAddress());
            $order->setShippingAddress($client->getShippingAddress());
            $order->getClientDetails()->setResetPasswordHash(null);
        }
        
        $this->createResource($order);
        
        return $order;
    }
    
    public function findOrder(string $sessionId, Client $client = null, Shop $shop)
    {
        if (null !== $client) {
            $order = $this->getCurrentClientOrder($client, $shop);
            if (null === $order) {
                $order = $this->getCurrentSessionOrder($sessionId, $shop);
            }
        } else {
            $order = $this->getCurrentSessionOrder($sessionId, $shop);
        }
        
        return $order;
    }
    
    private function getCurrentClientOrder(Client $client, Shop $shop)
    {
        return $this->getRepository()->findOneBy([
            'client'    => $client,
            'shop'      => $shop,
            'confirmed' => false,
        ]);
    }
    
    private function getCurrentSessionOrder($sessionId, Shop $shop)
    {
        return $this->getRepository()->findOneBy([
            'sessionId' => $sessionId,
            'shop'      => $shop,
            'confirmed' => false,
        ]);
    }
    
    private function isOrderDirty(Order $order, string $currency, Client $client = null, string $sessionId): bool
    {
        return $order->getClient() !== $client || $order->getCurrency() !== $currency || $order->getSessionId() !== $sessionId;
    }
}
