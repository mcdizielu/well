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
 * Class ExceptionControllerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ExceptionControllerTest extends AbstractFrontControllerTestCase
{
    public function test404Exception()
    {
        $url = $this->generateUrl('front.home_page.index') . '/foo/bar';
        $this->client->request('GET', $url);
        
        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}
