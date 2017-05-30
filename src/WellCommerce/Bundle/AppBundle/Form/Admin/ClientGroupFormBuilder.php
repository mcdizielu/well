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
namespace WellCommerce\Bundle\AppBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ClientGroupFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientGroupFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.client_group';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('client_group.repository')),
        ]));
        
        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
    
        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'description',
            'label' => 'common.label.description',
        ]));
        
        $discountSettings = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'discount_data',
            'label' => 'common.fieldset.discount_settings',
        ]));
        
        $discountSettings->addChild($this->getElement('tip', [
            'tip' => 'common.tip.discount_expression',
        ]));
        
        $discountSettings->addChild($this->getElement('text_field', [
            'name'   => 'discount',
            'label'  => 'common.label.discount',
            'suffix' => '%',
        ]));
        
        $minimumOrderAmount = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'minimumOrderAmount',
            'label' => 'common.fieldset.minimum_order_amount',
        ]));
        
        $minimumOrderAmount->addChild($this->getElement('text_field', [
            'name'    => 'minimumOrderAmount.value',
            'label'   => 'common.label.minimum_order_amount.value',
            'suffix'  => '%',
            'filters' => [
                $this->getFilter('comma_to_dot_changer'),
            ],
            'rules'   => [
                $this->getRule('required'),
            ],
            'default' => 0,
        ]));
        
        $minimumOrderAmount->addChild($this->getElement('select', [
            'name'    => 'minimumOrderAmount.currency',
            'label'   => 'common.label.minimum_order_amount.currency',
            'options' => $this->get('currency.dataset.admin')->getResult('select', ['order_by' => 'code'], [
                'label_column' => 'code',
                'value_column' => 'code',
            ]),
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
