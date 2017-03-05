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

use WellCommerce\Bundle\AppBundle\Entity\Dictionary;
use WellCommerce\Bundle\CoreBundle\Test\Controller\Admin\AbstractAdminControllerTestCase;

/**
 * Class DashboardControllerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class DashboardControllerTest extends AbstractAdminControllerTestCase
{
    public function testIndexAction()
    {
        $url     = $this->generateUrl('admin.dashboard.index');
        $crawler = $this->client->request('GET', $url);
        
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('dashboard.heading.index') . '")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('dashboard.heading.carts') . '")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('dashboard.heading.last_opinions') . '")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('dashboard.heading.last_orders') . '")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('dashboard.heading.new_customers') . '")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("' . $this->jsDataGridClass . '")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("' . $this->jsFormClass . '")')->count());
    }
}
