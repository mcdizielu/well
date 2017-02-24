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
namespace WellCommerce\Bundle\AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use WellCommerce\Bundle\AppBundle\Entity\Currency;
use WellCommerce\Bundle\AppBundle\Entity\Locale;
use WellCommerce\Bundle\CoreBundle\EventListener\AbstractEventSubscriber;

/**
 * Class CurrencySubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CurrencySubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -100],
        ];
    }
    
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        
        if (!$session->has('_currency')) {
            $currency = $this->getLocaleCurrency($request);
            if (null !== $currency) {
                $session->set('_currency', $currency);
            }
        }
    }
    
    private function getLocaleCurrency(Request $request)
    {
        $currentLocale = $request->getLocale();
        $repository    = $this->container->get('locale.repository');
        $locale        = $repository->findOneBy(['code' => $currentLocale]);
        
        if ($locale instanceof Locale && $locale->getCurrency() instanceof Currency) {
            return $locale->getCurrency()->getCode();
        }
        
        return $repository->findOneBy([])->getCurrency()->getCode();
    }
}
