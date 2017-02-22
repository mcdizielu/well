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
 * Class DictionaryFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class DictionaryFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.dictionary';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general'
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'identifier',
            'label' => 'dictionary.label.identifier',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('dictionary.repository'))
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'value',
            'label' => 'dictionary.label.value',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
