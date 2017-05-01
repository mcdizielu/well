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

namespace WellCommerce\Bundle\LastViewedBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CatalogBundle\Event\ProductViewedEvent;
use WellCommerce\Bundle\CoreBundle\EventListener\AbstractEventSubscriber;
use WellCommerce\Bundle\LastViewedBundle\Manager\LastViewedManager;

/**
 * Class LastViewedSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LastViewedSubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            ProductViewedEvent::EVENT_NAME => 'onProductViewed',
            'security.interactive_login'   => ['onSecurityInteractiveLogin', 0],
        ];
    }
    
    public function onProductViewed(ProductViewedEvent $event)
    {
        $client    = $this->getSecurityHelper()->getCurrentClient();
        $sessionId = $this->getRequestHelper()->getSessionId();
        $this->getManager()->updateLastViewedProduct($event->getProduct(), $client, $sessionId);
    }
    
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $sessionId = $this->getRequestHelper()->getSessionId();
        $user      = $event->getAuthenticationToken()->getUser();
        if ($user instanceof Client) {
            $this->getManager()->refreshSessionLastViewed($user, $sessionId);
        }
    }
    
    private function getManager(): LastViewedManager
    {
        return $this->get('last_viewed.manager');
    }
}
