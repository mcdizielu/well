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
namespace WellCommerce\Bundle\CouponBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\DataTransformer\DateTransformer;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class CouponForm
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CouponFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.coupon';
    }
    
    public function buildForm(FormInterface $form)
    {
        $currencies = $this->get('currency.dataset.admin')->getResult('select', ['order_by' => 'code'], [
            'label_column' => 'code',
            'value_column' => 'code'
        ]);

        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general'
        ]));

        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('coupon.repository'))
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $languageData->addChild($this->getElement('text_area', [
            'name'  => 'description',
            'label' => 'common.label.description',
        ]));

        $requiredData->addChild($this->getElement('date', [
            'name'        => 'validFrom',
            'label'       => 'common.label.valid_from',
            'transformer' => new DateTransformer('m/d/Y'),
        ]));

        $requiredData->addChild($this->getElement('date', [
            'name'        => 'validTo',
            'label'       => 'common.label.valid_to',
            'transformer' => new DateTransformer('m/d/Y'),
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'code',
            'label' => 'common.label.code',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'clientUsageLimit',
            'label' => 'coupon.label.client_usage_limit',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'globalUsageLimit',
            'label' => 'coupon.label.global_usage_limit',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $discountPane = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'discount_pane',
            'label' => 'coupon.fieldset.discount_settings'
        ]));
    
        $discountPane->addChild($this->getElement('select', [
            'name'         => 'currency',
            'label'        => 'common.label.currency',
            'options'      => $currencies,
        ]));
        
        $discountPane->addChild($this->getElement('select', [
            'name'    => 'modifierType',
            'label'   => 'coupon.label.modifier_type',
            'options' => $this->getModifierTypes()
        ]));
        
        $discountPane->addChild($this->getElement('text_field', [
            'name'  => 'modifierValue',
            'label' => 'coupon.label.modifier_value',
            'rules' => [
                $this->getRule('required')
            ],
        ]));
    
        $discountPane->addChild($this->getElement('text_field', [
            'name'  => 'minimumOrderValue',
            'label' => 'coupon.label.minimum_order_value',
            'rules' => [
                $this->getRule('required')
            ],
        ]));
    
        $discountPane->addChild($this->getElement('checkbox', [
            'name'  => 'excludePromotions',
            'label' => 'coupon.label.exclude_promotions',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }

    protected function getModifierTypes()
    {
        return [
            '%' => 'coupon.label.modifier_type_percent',
            '-' => 'coupon.label.modifier_type_subtract',
        ];
    }
}
