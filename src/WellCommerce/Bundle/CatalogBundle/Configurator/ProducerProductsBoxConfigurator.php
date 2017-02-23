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

namespace WellCommerce\Bundle\CatalogBundle\Configurator;

use WellCommerce\Bundle\CatalogBundle\Controller\Box\ProducerProductsBoxController;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;

/**
 * Class ProducerProductsBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ProducerProductsBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    public function __construct(ProducerProductsBoxController $controller)
    {
        $this->controller = $controller;
    }
    
    public function getType(): string
    {
        return 'ProducerProducts';
    }
}
