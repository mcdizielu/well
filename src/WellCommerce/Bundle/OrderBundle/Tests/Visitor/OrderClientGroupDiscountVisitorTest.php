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
use WellCommerce\Bundle\AppBundle\Helper\TaxHelper;
use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class OrderClientGroupDiscountVisitorTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderClientGroupDiscountVisitorTest extends AbstractTestCase
{
    /**
     * @dataProvider expressionProvider
     */
    public function testModifierValue($expression, $grossTotal, $expectedDiscount)
    {
        $visitor = $this->container->get('client_group_discount.order.visitor');
        $order   = new Order();
        $order->getProductTotal()->setGrossPrice($grossTotal);
        $order->getProductTotal()->setNetPrice($netTotal = TaxHelper::calculateNetPrice($grossTotal, 23));
        $order->getProductTotal()->setTaxAmount($grossTotal - $netTotal);
        $order->setCurrency('EUR');
        
        $clientGroup = new ClientGroup();
        $clientGroup->setDiscount($expression);
        
        $client = new Client();
        $client->setClientGroup($clientGroup);
        $order->setClient($client);
        
        $visitor->visitOrder($order);
        
        $this->assertEquals($expectedDiscount, $order->getModifier('client_group_discount')->getGrossAmount());
    }
    
    public function expressionProvider()
    {
        return [
            [10, 1200, 120],
            [15, 100, 15],
            ['10 + 5', 100, 15],
            ['order.getProductTotal().getGrossPrice() / 10', 100, 10],
            ['(order.getProductTotal().getGrossPrice() / 10) + 1', 100, 11],
            ['order.getCurrency() in ["EUR"] ? 7 : 0', 100, 7],
            ['order.getCurrency() not in ["EUR"] ? 0 : 1', 100, 1],
        ];
    }
}
