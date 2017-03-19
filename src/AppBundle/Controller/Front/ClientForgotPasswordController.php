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

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;

/**
 * Class ClientForgotPasswordController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientForgotPasswordController extends AbstractFrontController
{
    public function resetAction()
    {
        if ($this->getSecurityHelper()->getCurrentUser() instanceof Client) {
            return $this->redirectToRoute('front.client_settings.index');
        }
        
        return $this->displayTemplate('reset');
    }
    
    public function changeAction()
    {
        if ($this->getSecurityHelper()->getCurrentUser() instanceof Client) {
            return $this->redirectToRoute('front.client_settings.index');
        }
        
        return $this->displayTemplate('change');
    }
}
