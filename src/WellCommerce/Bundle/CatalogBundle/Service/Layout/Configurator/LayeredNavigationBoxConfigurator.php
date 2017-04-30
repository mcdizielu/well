<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CatalogBundle\Service\Layout\Configurator;

use WellCommerce\Bundle\CatalogBundle\Controller\Box\LayeredNavigationBoxController;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class LayeredNavigationBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class LayeredNavigationBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    public function __construct(LayeredNavigationBoxController $controller)
    {
        $this->controller = $controller;
    }
    
    public function getType(): string
    {
        return 'LayeredNavigation';
    }
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        
        $fieldset->addChild($builder->getElement('tip', [
            'tip' => 'layout_box.layered_navigation.tip',
        ]));
    
        $fieldset->addChild($builder->getElement('checkbox', [
            'name'    => 'enable_price_filter',
            'label'   => 'layout_box.layered_navigation.enable_price_filter',
        ]))->setValue($this->getValue($defaults, '[enable_price_filter]', true));
    
        $fieldset->addChild($builder->getElement('checkbox', [
            'name'    => 'enable_brand_filter',
            'label'   => 'layout_box.layered_navigation.enable_brand_filter',
        ]))->setValue($this->getValue($defaults, '[enable_brand_filter]', true));
    
        $fieldset->addChild($builder->getElement('checkbox', [
            'name'    => 'enable_attribute_filter',
            'label'   => 'layout_box.layered_navigation.enable_attribute_filter',
        ]))->setValue($this->getValue($defaults, '[enable_attribute_filter]', true));

        $fieldset->addChild($builder->getElement('checkbox', [
            'name'    => 'enable_status_filter',
            'label'   => 'layout_box.layered_navigation.enable_status_filter',
        ]))->setValue($this->getValue($defaults, '[enable_status_filter]', true));
    }
}
