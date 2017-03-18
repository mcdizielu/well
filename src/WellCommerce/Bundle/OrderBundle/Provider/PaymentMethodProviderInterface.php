<?php

namespace WellCommerce\Bundle\OrderBundle\Provider;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Interface PaymentMethodProviderInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface PaymentMethodProviderInterface
{
    public function getPaymentMethodsForOrder(Order $order): Collection;
}
