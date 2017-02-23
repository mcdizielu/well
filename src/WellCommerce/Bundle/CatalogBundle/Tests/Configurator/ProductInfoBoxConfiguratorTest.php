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

namespace WellCommerce\Bundle\CatalogBundle\Tests\Manager;

use WellCommerce\Bundle\CatalogBundle\Controller\Box\ProductInfoBoxController;
use WellCommerce\Bundle\CoreBundle\Test\Configurator\AbstractLayoutBoxConfiguratorTestCase;
use WellCommerce\Component\Layout\Configurator\LayoutBoxConfiguratorInterface;

/**
 * Class ProductInfoBoxConfiguratorTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductInfoBoxConfiguratorTest extends AbstractLayoutBoxConfiguratorTestCase
{
    protected function getService(): LayoutBoxConfiguratorInterface
    {
        return $this->container->get('product_info.layout_box.configurator');
    }
    
    public function provideLayoutBoxConfiguration()
    {
        return [
            ['ProductInfo', ProductInfoBoxController::class],
        ];
    }
}
