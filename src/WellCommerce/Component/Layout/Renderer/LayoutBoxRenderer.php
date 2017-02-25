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

namespace WellCommerce\Component\Layout\Renderer;

use ReflectionClass;
use ReflectionMethod;
use WellCommerce\Bundle\CoreBundle\Helper\Router\RouterHelperInterface;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;
use WellCommerce\Component\Layout\Configurator\LayoutBoxConfiguratorCollection;
use WellCommerce\Component\Layout\Controller\BoxControllerInterface;
use WellCommerce\Component\Layout\Model\LayoutBoxInterface;

/**
 * Class LayoutBoxRenderer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class LayoutBoxRenderer implements LayoutBoxRendererInterface
{
    /**
     * @var LayoutBoxConfiguratorCollection
     */
    private $configurators;
    
    /**
     * @var RouterHelperInterface
     */
    private $routerHelper;
    
    public function __construct(LayoutBoxConfiguratorCollection $configurators, RouterHelperInterface $routerHelper)
    {
        $this->configurators = $configurators;
        $this->routerHelper  = $routerHelper;
    }
    
    public function render(LayoutBoxInterface $layoutBox, array $params): string
    {
        $controller = $this->getControllerService($layoutBox);
        $action     = $this->getControllerAction($controller);
        $settings   = $this->makeSettingsCollection($layoutBox, $params);
        $content    = call_user_func_array([$controller, $action], [$settings]);
        
        return $content->getContent();
    }
    
    private function makeSettingsCollection(LayoutBoxInterface $box, array $params = []): LayoutBoxSettingsCollection
    {
        $defaultSettings = $box->getSettings();
        $settings        = array_merge($defaultSettings, $params);
        $collection      = new LayoutBoxSettingsCollection();
        
        foreach ($settings as $name => $value) {
            $collection->add($name, $value);
        }
        
        $collection->add('name', $box->getBoxName());
        $collection->add('content', $box->getBoxContent());
        
        return $collection;
    }
    
    private function getControllerService(LayoutBoxInterface $layoutBox): BoxControllerInterface
    {
        $boxType      = $layoutBox->getBoxType();
        $configurator = $this->configurators->get($boxType);
        
        return $configurator->getController();
    }
    
    private function getControllerAction(BoxControllerInterface $controller): string
    {
        $currentAction = $this->routerHelper->getCurrentAction();
        
        if ($this->hasControllerAction($controller, $currentAction)) {
            return $currentAction;
        }
        
        return 'indexAction';
    }
    
    private function hasControllerAction(BoxControllerInterface $controller, string $action): bool
    {
        $reflectionClass = new ReflectionClass($controller);
        if ($reflectionClass->hasMethod($action)) {
            $reflectionMethod = new ReflectionMethod($controller, $action);
            if ($reflectionMethod->isPublic()) {
                return true;
            }
        }
        
        return false;
    }
}
