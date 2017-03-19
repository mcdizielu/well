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
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;

/**
 * Class ClientController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientController extends AbstractFrontController
{
    public function loginAction(): Response
    {
        if ($this->getSecurityHelper()->getCurrentClient() instanceof Client) {
            return $this->redirectToRoute('front.client_order.index');
        }
        
        return $this->displayTemplate('login');
    }
    
    public function loginCheckAction()
    {
    }
}
