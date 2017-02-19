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

namespace WellCommerce\Bundle\CoreBundle\Test\Controller\Front;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;

/**
 * Class AbstractFrontControllerTestCase
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AbstractFrontControllerTestCase extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    protected function logIn(): Client
    {
        $client   = $this->container->get('client.repository')->findOneBy([]);
        $session  = $this->client->getContainer()->get('session');
        $firewall = 'client';
        $token    = new UsernamePasswordToken('demo@wellcommerce.org', 'demo', $firewall, ['ROLE_CLIENT']);
        $token->setUser($client);
        
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();
        
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
        
        $this->container->get('security.token_storage')->setToken($token);
        
        return $client;
    }
    
    protected function logOut()
    {
        $this->client->request('GET', $this->generateUrl('front.client.logout'));
    }
    
    protected function generateUrl(string $routeName, array $params = [], $absolute = UrlGeneratorInterface::ABSOLUTE_URL): string
    {
        return $this->container->get('router')->generate($routeName, $params, $absolute);
    }
    
    protected function trans(string $message, string $domain = 'wellcommerce'): string
    {
        return $this->container->get('translator.helper')->trans($message, [], $domain);
    }
    
    protected function assertIsLoginRedirect()
    {
        $redirectUrl = $this->generateUrl('front.client.login');
        
        $this->assertTrue($this->client->getResponse()->isRedirect($redirectUrl), sprintf(
            'Location: %s, Redirect: %s',
            $this->client->getResponse()->headers->get('location'),
            $redirectUrl
        ));
        
    }
}
