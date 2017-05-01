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

namespace WellCommerce\Bundle\LastViewedBundle\Service\Layout\Configurator;

use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Bundle\LastViewedBundle\Controller\Box\LastViewedBoxController;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class LastViewedBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class LastViewedBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    public function __construct(LastViewedBoxController $controller)
    {
        $this->controller = $controller;
    }
    
    public function getType(): string
    {
        return 'LastViewed';
    }
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        
        $fieldset->addChild($builder->getElement('tip', [
            'tip' => 'layout_box.tip.last_viewed',
        ]));

        $fieldset->addChild($builder->getElement('text_field', [
            'name'  => 'limit',
            'label' => 'layout_box.label.last_viewed.limit',
        ]))->setValue($this->getValue($defaults, '[limit]', 4));
    }
}
