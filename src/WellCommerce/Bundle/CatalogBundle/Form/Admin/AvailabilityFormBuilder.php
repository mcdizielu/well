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
namespace WellCommerce\Bundle\CatalogBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class AvailabilityForm
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AvailabilityFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.availability';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'general_settings',
            'label' => 'common.fieldset.general'
        ]));

        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('availability.repository'))
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
