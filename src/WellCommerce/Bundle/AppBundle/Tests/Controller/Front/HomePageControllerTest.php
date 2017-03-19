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

namespace WellCommerce\Bundle\AppBundle\Tests\Controller\Front;

use WellCommerce\Bundle\CoreBundle\Test\Controller\Front\AbstractFrontControllerTestCase;

/**
 * Class HomePageControllerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class HomePageControllerTest extends AbstractFrontControllerTestCase
{
    public function testIndexAction()
    {
        $url     = $this->generateUrl('front.home_page.index');
        $crawler = $this->client->request('GET', $url);
        
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(0, $crawler->filter('html:contains("' . $this->trans('common.error.404') . '")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("' . $this->trans('common.error.500') . '")')->count());
    }
}
