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

namespace WellCommerce\Bundle\FeatureBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class FeatureFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureFormBuilder extends AbstractFormBuilder
{
    public function buildForm(FormInterface $form)
    {
        $groupData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'main_data',
            'label' => $this->trans('common.fieldset.general'),
        ]));
        
        
        $languageData = $groupData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => $this->trans('common.fieldset.translations'),
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('feature.repository')),
        ]));
        
        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => $this->trans('common.label.name'),
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $groupData->addChild($this->getElement('select', [
            'name'    => 'type',
            'label'   => $this->trans('feature.label.field_type.choose'),
            'options' => [
                1 => $this->trans('feature.label.field_type.text_field'),
                2 => $this->trans('feature.label.field_type.text_area'),
                3 => $this->trans('feature.label.field_type.checkbox'),
            ],
        ]));
        
        $groupsData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'groups_data',
            'label' => $this->trans('feature.fieldset.groups'),
        ]));
        
        $groupsData->addChild($this->getElement('multi_select', [
            'name'        => 'groups',
            'label'       => $this->trans('feature.label.groups'),
            'options'     => $this->get('feature_group.dataset.admin')->getResult('select', ['limit' => 10000]),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('feature_group.repository')),
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    public function getAlias(): string
    {
        return 'admin.feature';
    }
}
