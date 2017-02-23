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
 * Class CompanyFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class CompanyFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.company';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general'
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'company.label.name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'shortName',
            'label' => 'company.label.short_name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $addressData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'address_data',
            'label' => 'address.label.addresses'
        ]));

        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'line1',
            'label' => 'address.label.line1',
        ]));

        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'line2',
            'label' => 'address.label.line2',
        ]));

        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'state',
            'label' => 'address.label.state',
        ]));

        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'postalCode',
            'label' => 'address.label.post_code',
        ]));

        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'city',
            'label' => 'address.label.city',
        ]));

        $addressData->addChild($this->getElement('select', [
            'name'    => 'country',
            'label'   => 'address.label.country',
            'options' => $this->get('country.repository')->all()
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
