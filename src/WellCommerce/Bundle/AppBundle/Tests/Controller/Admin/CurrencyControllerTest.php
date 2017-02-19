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

namespace WellCommerce\Bundle\AppBundle\Tests\Controller\Admin;

use WellCommerce\Bundle\AppBundle\Entity\Currency;
use WellCommerce\Bundle\CoreBundle\Test\Controller\Admin\AbstractAdminControllerTestCase;

/**
 * Class CurrencyControllerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CurrencyControllerTest extends AbstractAdminControllerTestCase
{
    public function testIndexAction()
    {
        $url     = $this->generateUrl('admin.currency.index');
        $crawler = $this->client->request('GET', $url);
        
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('currency.heading.index') . '")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->jsDataGridClass . '")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("' . $this->jsFormClass . '")')->count());
    }
    
    public function testAddAction()
    {
        $url     = $this->generateUrl('admin.currency.add');
        $crawler = $this->client->request('GET', $url);
        
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('currency.heading.add') . '")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("' . $this->jsDataGridClass . '")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->jsFormClass . '")')->count());
    }
    
    public function testEditAction()
    {
        $collection = $this->container->get('currency.repository')->getCollection();
        
        $collection->map(function (Currency $currency) {
            $url     = $this->generateUrl('admin.currency.edit', ['id' => $currency->getId()]);
            $crawler = $this->client->request('GET', $url);
            
            $this->assertTrue($this->client->getResponse()->isSuccessful());
            $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('currency.heading.edit') . '")')->count());
            $this->assertEquals(0, $crawler->filter('html:contains("' . $this->jsDataGridClass . '")')->count());
            $this->assertEquals(1, $crawler->filter('html:contains("' . $this->jsFormClass . '")')->count());
            $this->assertEquals(1, $crawler->filter('html:contains("' . $currency->getCode() . '")')->count());
        });
    }
    
    public function testGridAction()
    {
        $this->client->request('GET', $this->generateUrl('admin.currency.grid'), [], [], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);
        
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
