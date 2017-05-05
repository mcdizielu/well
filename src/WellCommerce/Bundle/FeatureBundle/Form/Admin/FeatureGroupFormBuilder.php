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
 * Class FeatureGroupFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureGroupFormBuilder extends AbstractFormBuilder
{
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => $this->trans('common.fieldset.general'),
        ]));
        
        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => $this->trans('common.fieldset.translations'),
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('feature_group.repository')),
        ]));
        
        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => $this->trans('common.label.name'),
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $setsData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'sets_data',
            'label' => $this->trans('feature_group.fieldset.sets'),
        ]));
        
        $setsData->addChild($this->getElement('multi_select', [
            'name'        => 'sets',
            'label'       => $this->trans('feature_group.label.sets'),
            'options'     => $this->get('feature_set.dataset.admin')->getResult('select', ['limit' => 10000]),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('feature_set.repository')),
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    public function getAlias(): string
    {
        return 'admin.feature_group';
    }
}
