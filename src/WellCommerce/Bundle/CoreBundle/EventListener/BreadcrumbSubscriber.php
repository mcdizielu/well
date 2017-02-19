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
namespace WellCommerce\Bundle\CoreBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use WellCommerce\Bundle\RoutingBundle\Helper\RouterHelperInterface;
use WellCommerce\Component\Breadcrumb\Model\Breadcrumb;
use WellCommerce\Component\Breadcrumb\Provider\BreadcrumbProviderInterface;

/**
 * Class BreadcrumbSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class BreadcrumbSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterHelperInterface
     */
    private $routerHelper;
    
    /**
     * @var BreadcrumbProviderInterface
     */
    private $breadcrumbProvider;
    
    public function __construct(RouterHelperInterface $routerHelper, BreadcrumbProviderInterface $breadcrumbProvider)
    {
        $this->routerHelper       = $routerHelper;
        $this->breadcrumbProvider = $breadcrumbProvider;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 0],
        ];
    }
    
    public function onKernelRequest()
    {
        $currentRoute = $this->routerHelper->getCurrentRoute();
        
        if ($currentRoute->hasOption('breadcrumb')) {
            $options = $currentRoute->getOption('breadcrumb');
            
            $this->breadcrumbProvider->add(new Breadcrumb([
                'label' => $options['label'],
                'url'   => $currentRoute->getPath(),
            ]));
        }
    }
}
