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

namespace WellCommerce\Bundle\OrderBundle\Tests\Visitor;

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\ClientGroup;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class MinimumAmountVisitorTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class MinimumAmountVisitorTest extends AbstractTestCase
{
    /**
     * @dataProvider valueProvider
     */
    public function testMinimumValue($clientValue, $clientGroupValue, $shopValue, $expectedValue)
    {
        $currency = 'EUR';
        $visitor  = $this->container->get('minimum_value.order.visitor');
        $order    = new Order();
        $order->setCurrency($currency);
        
        $shop = new Shop();
        $shop->getMinimumOrderAmount()->setCurrency($currency);
        $shop->getMinimumOrderAmount()->setValue($shopValue);
        
        $clientGroup = new ClientGroup();
        $clientGroup->getMinimumOrderAmount()->setCurrency($currency);
        $clientGroup->getMinimumOrderAmount()->setValue($clientGroupValue);
        
        $client = new Client();
        $client->getMinimumOrderAmount()->setCurrency($currency);
        $client->getMinimumOrderAmount()->setValue($clientValue);
        $client->setClientGroup($clientGroup);
        
        $order->setShop($shop);
        $order->setClient($client);
        
        $visitor->visitOrder($order);
        
        $this->assertEquals($expectedValue, $order->getMinimumOrderAmount()->getValue());
        $this->assertEquals($currency, $order->getMinimumOrderAmount()->getCurrency());
    }
    
    public function valueProvider()
    {
        return [
            [0, 0, 100, 100],
            [100, 101, 102, 100],
            [102, 0, 0, 102],
            [0, 0, 0, 0],
            [100.11, 100.12, 100.13, 100.11],
            [0, 100.12, 100.13, 100.12],
        ];
    }
}
