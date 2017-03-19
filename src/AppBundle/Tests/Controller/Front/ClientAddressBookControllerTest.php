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
 * Class ClientControllerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientAddressBookControllerTest extends AbstractFrontControllerTestCase
{
    public function testIndexLoggedAction()
    {
        $this->logIn();
        
        $url     = $this->generateUrl('front.client_address_book.index');
        $crawler = $this->client->request('GET', $url);
        
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('client.heading.billing_address') . '")')->count());
        
        $this->logOut();
    }
    
    public function testIndexNonLoggedAction()
    {
        $this->client->request('GET', $this->generateUrl('front.client_address_book.index'));
        $this->assertIsLoginRedirect();
    }
}
