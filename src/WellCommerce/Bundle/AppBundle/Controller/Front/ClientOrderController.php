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

namespace WellCommerce\Bundle\AppBundle\Controller\Front;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;
use WellCommerce\Bundle\OrderBundle\Manager\OrderProductManager;

/**
 * Class ClientOrderController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientOrderController extends AbstractFrontController
{
    public function indexAction(): Response
    {
        return $this->displayTemplate('index');
    }
    
    public function viewAction(): Response
    {
        return $this->displayTemplate('view');
    }
    
    public function repeatAction(Order $repeatedOrder): Response
    {
        /** @var OrderProductManager $orderProductManager */
        $orderProductManager = $this->get('order_product.manager');
        $currentOrder        = $this->getOrderProvider()->getCurrentOrder();
        $client              = $this->getSecurityHelper()->getAuthenticatedClient();
        if ($client->getId() === $repeatedOrder->getClient()->getId()) {
            $repeatedOrder->getProducts()->map(function (OrderProduct $orderProduct) use ($currentOrder, $orderProductManager) {
                $orderProductManager->addProductToOrder(
                    $orderProduct->getProduct(),
                    $orderProduct->getVariant(),
                    $orderProduct->getQuantity(),
                    $currentOrder
                );
            });
            
            return $this->redirectToRoute('front.cart.index');
        }
        
        return $this->redirectToAction('index');
    }
}
