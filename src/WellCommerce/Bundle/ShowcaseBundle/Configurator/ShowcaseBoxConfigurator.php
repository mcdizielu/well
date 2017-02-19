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

namespace WellCommerce\Bundle\ShowcaseBundle\Configurator;

use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class ShowcaseBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ShowcaseBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    /**
     * {@inheritdoc}
     */
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        
        $fieldset->addChild($builder->getElement('tip', [
            'tip' => $this->trans('layout_box.showcase.tip'),
        ]));
        
        $status = $fieldset->addChild($builder->getElement('select', [
            'name'    => 'status',
            'label'   => $this->trans('showcase.label.status'),
            'options' => $this->get('product_status.dataset.admin')->getResult('select'),
        ]));
        
        $status->setValue($this->getPropertyAccessor()->getValue($defaults, '[status]'));
    }
}
