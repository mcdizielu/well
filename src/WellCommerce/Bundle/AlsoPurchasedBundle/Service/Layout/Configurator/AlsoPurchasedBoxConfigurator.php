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

namespace WellCommerce\Bundle\AlsoPurchasedBundle\Service\Layout\Configurator;

use WellCommerce\Bundle\AlsoPurchasedBundle\Controller\Box\AlsoPurchasedBoxController;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class LastViewedBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AlsoPurchasedBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    public function __construct(AlsoPurchasedBoxController $controller)
    {
        $this->controller = $controller;
    }
    
    public function getType(): string
    {
        return 'AlsoPurchased';
    }
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        
        $fieldset->addChild($builder->getElement('tip', [
            'tip' => 'layout_box.tip.also_purchased',
        ]));
        
        $fieldset->addChild($builder->getElement('text_field', [
            'name'  => 'limit',
            'label' => 'layout_box.label.also_purchased.limit',
        ]))->setValue($this->getValue($defaults, '[limit]', 4));
    }
}
