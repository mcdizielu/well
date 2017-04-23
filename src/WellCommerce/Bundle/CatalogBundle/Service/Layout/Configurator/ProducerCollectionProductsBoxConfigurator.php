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

namespace WellCommerce\Bundle\CatalogBundle\Service\Layout\Configurator;

use WellCommerce\Bundle\CatalogBundle\Controller\Box\ProducerCollectionProductsBoxController;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;

/**
 * Class ProducerCollectionProductsBoxConfigurator
 *
 * @author Rafał Martonik <rafal@wellcommerce.org>
 */
final class ProducerCollectionProductsBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    public function __construct(ProducerCollectionProductsBoxController $controller)
    {
        $this->controller = $controller;
    }
    
    public function getType(): string
    {
        return 'ProducerCollectionProducts';
    }
}
