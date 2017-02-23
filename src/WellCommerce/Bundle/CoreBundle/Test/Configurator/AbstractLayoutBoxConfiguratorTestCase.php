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

namespace WellCommerce\Bundle\CoreBundle\Test\Configurator;

use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;
use WellCommerce\Component\Layout\Configurator\LayoutBoxConfiguratorInterface;
use WellCommerce\Component\Layout\Controller\BoxControllerInterface;

/**
 * Class AbstractLayoutBoxConfiguratorTestCase
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractLayoutBoxConfiguratorTestCase extends AbstractTestCase
{
    /**
     * @dataProvider provideLayoutBoxConfiguration
     */
    public function testConfiguration(string $type, string $controllerClass)
    {
        $configurator = $this->getService();
        $this->assertEquals($type, $configurator->getType());
        $this->assertInstanceOf($controllerClass, $configurator->getController());
        $this->assertInstanceOf(BoxControllerInterface::class, $configurator->getController());
    }
    
    abstract protected function getService(): LayoutBoxConfiguratorInterface;
}
