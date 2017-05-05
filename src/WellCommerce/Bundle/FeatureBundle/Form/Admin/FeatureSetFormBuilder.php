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
 * Class FeatureSetFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureSetFormBuilder extends AbstractFormBuilder
{
    public function buildForm(FormInterface $form)
    {
        $groupData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'main_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $languageData = $groupData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('feature_set.repository')),
        ]));
        
        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $groupsData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'groups_data',
            'label' => 'feature_set.fieldset.groups',
        ]));
        
        $groupsData->addChild($this->getElement('multi_select', [
            'name'        => 'groups',
            'label'       => 'feature_set.label.groups',
            'options'     => $this->get('feature_group.dataset.admin')->getResult('select', ['limit' => 10000]),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('feature_group.repository')),
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    public function getAlias(): string
    {
        return 'admin.feature_set';
    }
}
