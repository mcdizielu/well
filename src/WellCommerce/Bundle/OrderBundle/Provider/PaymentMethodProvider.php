<?php

namespace WellCommerce\Bundle\OrderBundle\Provider;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\PaymentMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;

/**
 * Class PaymentMethodProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PaymentMethodProvider implements PaymentMethodProviderInterface
{
    public function getPaymentMethodsForOrder(Order $order): Collection
    {
        $collection = new ArrayCollection();
        
        if ($order->getShippingMethod() instanceof ShippingMethod) {
            $collection = $order->getShippingMethod()->getPaymentMethods();
            $collection = $collection->filter(function (PaymentMethod $paymentMethod) use ($order) {
                return $paymentMethod->isEnabled() && $this->isPaymentMethodAvailableForOrder($paymentMethod, $order);
            });
        }
        
        return $collection;
    }
    
    private function isPaymentMethodAvailableForOrder(PaymentMethod $method, Order $order)
    {
        $client       = $order->getClient();
        $clientGroups = $method->getClientGroups();
        $clientGroup  = null !== $client ? $client->getClientGroup() : null;
        
        if ($clientGroups->count()) {
            return $clientGroups->contains($clientGroup);
        }
        
        return true;
    }
}
