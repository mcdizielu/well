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
 * Class ClientForgotPasswordControllerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientForgotPasswordControllerTest extends AbstractFrontControllerTestCase
{
    public function testResetNonLoggedAction()
    {
        $this->logOut();
        
        $url     = $this->generateUrl('front.client_password.reset');
        $crawler = $this->client->request('GET', $url);
        
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->trans('client.heading.reset_password') . '")')->count());
    }
    
    public function testResetLoggedAction()
    {
        $this->logIn();
        
        $this->client->request('GET', $this->generateUrl('front.client_password.reset'));
        $redirectUrl = $this->generateUrl('front.client_settings.index');
        
        $this->assertTrue($this->client->getResponse()->isRedirect($redirectUrl), sprintf(
            'Location: %s, Redirect: %s',
            $this->client->getResponse()->headers->get('location'),
            $redirectUrl
        ));
    }
}
