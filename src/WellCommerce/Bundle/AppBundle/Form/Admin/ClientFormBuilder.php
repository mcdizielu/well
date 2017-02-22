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
 * Class ClientFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.client';
    }
    
    public function buildForm(FormInterface $form)
    {
        $countries      = $this->get('country.repository')->all();
        $defaultCountry = $this->getShopStorage()->getCurrentShop()->getDefaultCountry();
        
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $requiredData->addChild($this->getElement('select', [
            'name'        => 'shop',
            'label'       => 'common.label.shop',
            'options'     => $this->get('shop.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('shop.repository')),
        ]));
        
        $requiredData->addChild($this->getElement('select', [
            'name'        => 'clientGroup',
            'label'       => 'common.label.client_group',
            'options'     => $this->get('client_group.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('client_group.repository')),
        ]));
        
        $clientDetailsData = $requiredData->addChild($this->getElement('nested_fieldset', [
            'name'  => 'clientDetails',
            'label' => 'client.heading.client_details',
        ]));
        
        $clientDetailsData->addChild($this->getElement('text_field', [
            'name'  => 'clientDetails.username',
            'label' => 'client.label.username',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $clientDetailsData->addChild($this->getElement('checkbox', [
            'name'    => 'clientDetails.conditionsAccepted',
            'label'   => 'client.label.accept_conditions',
            'default' => true,
            'comment' => 'client.label.accept_conditions',
        ]));
        
        $clientDetailsData->addChild($this->getElement('checkbox', [
            'name'    => 'clientDetails.newsletterAccepted',
            'label'   => 'client.label.accept_newsletter',
            'comment' => 'client.label.accept_newsletter',
        ]));
        
        $clientDetailsData->addChild($this->getElement('text_field', [
            'name'    => 'clientDetails.discount',
            'label'   => 'common.label.discount',
            'suffix'  => '%',
            'filters' => [
                $this->getFilter('comma_to_dot_changer'),
            ],
            'rules'   => [
                $this->getRule('required'),
            ],
            'default' => 0,
        ]));
        
        if ($this->getRouterHelper()->getCurrentAction() === 'addAction') {
            $clientDetailsData->addChild($this->getElement('text_field', [
                'name'  => 'clientDetails.hashedPassword',
                'label' => 'client.label.password',
            ]))->setValue($this->getSecurityHelper()->generateRandomPassword());
        }
        
        $contactDetailsData = $requiredData->addChild($this->getElement('nested_fieldset', [
            'name'  => 'contactDetails',
            'label' => 'client.heading.contact_details',
        ]));
        
        $contactDetailsData->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.firstName',
            'label' => 'common.label.first_name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $contactDetailsData->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.lastName',
            'label' => 'common.label.last_name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $contactDetailsData->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.phone',
            'label' => 'common.label.phone',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $billingAddress = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'billingAddress',
            'label' => 'client.heading.billing_address',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.firstName',
            'label' => 'client.label.address.first_name',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.lastName',
            'label' => 'client.label.address.last_name',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.line1',
            'label' => 'client.label.address.line1',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.line2',
            'label' => 'client.label.address.line2',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.postalCode',
            'label' => 'client.label.address.postal_code',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.province',
            'label' => 'client.label.address.province',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.city',
            'label' => 'client.label.address.city',
        ]));
        
        $billingAddress->addChild($this->getElement('select', [
            'name'    => 'billingAddress.country',
            'label'   => 'client.label.address.country',
            'options' => $countries,
            'default' => $defaultCountry,
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.vatId',
            'label' => 'client.label.address.vat_id',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.companyName',
            'label' => 'client.label.address.company_name',
        ]));
        
        $shippingAddress = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'shippingAddress',
            'label' => 'client.heading.shipping_address',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'  => 'shippingAddress.firstName',
            'label' => 'client.label.address.first_name',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'  => 'shippingAddress.lastName',
            'label' => 'client.label.address.last_name',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'  => 'shippingAddress.line1',
            'label' => 'client.label.address.line1',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'  => 'shippingAddress.companyName',
            'label' => 'client.label.address.company_name',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'  => 'shippingAddress.line2',
            'label' => 'client.label.address.line2',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'  => 'shippingAddress.postalCode',
            'label' => 'client.label.address.postal_code',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'  => 'shippingAddress.state',
            'label' => 'client.label.address.state',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'  => 'shippingAddress.city',
            'label' => 'client.label.address.city',
        ]));
        
        $shippingAddress->addChild($this->getElement('select', [
            'name'    => 'shippingAddress.country',
            'label'   => 'client.label.address.country',
            'options' => $countries,
            'default' => $defaultCountry,
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
