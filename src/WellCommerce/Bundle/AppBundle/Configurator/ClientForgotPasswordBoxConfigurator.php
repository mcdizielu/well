<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\AppBundle\Configurator;

use WellCommerce\Bundle\AppBundle\Controller\Box\ClientForgotPasswordBoxController;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Component\Layout\Controller\BoxControllerInterface;

/**
 * Class ClientForgotPasswordBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ClientForgotPasswordBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    public function __construct(ClientForgotPasswordBoxController $controller)
    {
        $this->controller = $controller;
    }
    
    public function getType(): string
    {
        return 'ClientForgotPassword';
    }
}
