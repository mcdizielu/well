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

namespace WellCommerce\Bundle\OAuthBundle\Controller\Front;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;

/**
 * Class OAuthController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OAuthController extends AbstractFrontController
{
    public function connectAction(Request $request, string $provider)
    {
        /** @var Collection $providers */
        $providers = $this->get('oauth.provider.collection');
        if (false === $providers->containsKey($provider)) {
            return $this->redirectToRoute('front.client.login');
        }

        $this->getRequestHelper()->setSessionAttribute('_oauth_provider', $provider);

        return $this->get('oauth.authenticator')->start($request);
    }
    
    public function checkAction()
    {
    }
}
