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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use WellCommerce\Bundle\AppBundle\Storage\ShopStorageInterface;
use WellCommerce\Bundle\AppBundle\Storage\ThemeStorageInterface;

/**
 * Class ThemeSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ThemeSubscriber implements EventSubscriberInterface
{
    /**
     * @var ThemeStorageInterface
     */
    private $themeStorage;
    
    /**
     * @var ShopStorageInterface
     */
    private $shopStorage;
    
    public function __construct(ThemeStorageInterface $themeStorage, ShopStorageInterface $shopStorage)
    {
        $this->themeStorage = $themeStorage;
        $this->shopStorage  = $shopStorage;
    }
    
    public function onKernelController()
    {
        $this->themeStorage->setCurrentTheme($this->shopStorage->getCurrentShop()->getTheme());
    }
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -10],
        ];
    }
}
