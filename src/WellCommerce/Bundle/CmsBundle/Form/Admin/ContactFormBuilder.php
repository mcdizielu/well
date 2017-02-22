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
namespace WellCommerce\Bundle\CmsBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ContactFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ContactFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.contact';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'requiredData',
            'label' => 'common.fieldset.general'
        ]));

        $requiredData->addChild($this->getElement('checkbox', [
            'name'  => 'enabled',
            'label' => 'common.label.enabled',
        ]));

        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('contact.repository'))
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'email',
            'label' => 'common.label.email',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'phone',
            'label' => 'common.label.phone',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $languageData->addChild($this->getElement('text_area', [
            'name'  => 'businessHours',
            'label' => 'contact.label.business_hours',
        ]));

        $addressData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'addressData',
            'label' => 'common.label.address'
        ]));

        $languageData = $addressData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('contact.repository'))
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'line1',
            'label' => 'address.label.line1',
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'line2',
            'label' => 'address.label.line2',
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'state',
            'label' => 'address.label.state',
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'postalCode',
            'label' => 'address.label.post_code',
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'city',
            'label' => 'address.label.city',
        ]));

        $languageData->addChild($this->getElement('select', [
            'name'    => 'country',
            'label'   => 'address.label.country',
            'options' => $this->get('country.repository')->all()
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
