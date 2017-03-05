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

namespace WellCommerce\Bundle\AppBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Repository\OrderRepositoryInterface;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class ClientOrderBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientOrderBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $orders = $this->getOrderRepository()->getClientOrdersCollection($this->getAuthenticatedClient());
        
        return $this->displayTemplate('index', [
            'orders'      => $orders,
            'boxSettings' => $boxSettings,
        ]);
    }
    
    public function viewAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $order = $this->getOrderRepository()->findOneBy([
            'id'     => (int)$this->getRequestHelper()->getAttributesBagParam('id'),
            'client' => $this->getAuthenticatedClient(),
        ]);
        
        if (!$order instanceof Order) {
            return $this->redirectToAction('index');
        }
        
        return $this->displayTemplate('view', [
            'order'       => $order,
            'boxSettings' => $boxSettings,
        ]);
    }
    
    private function getOrderRepository(): OrderRepositoryInterface
    {
        return $this->get('order.repository');
    }
}
