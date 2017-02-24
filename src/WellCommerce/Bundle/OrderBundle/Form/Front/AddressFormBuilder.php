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
namespace WellCommerce\Bundle\OrderBundle\Form\Front;

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class AddressFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AddressFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'front.address';
    }
    
    public function buildForm(FormInterface $form)
    {
        $form->addChild($this->getElement('checkbox', [
            'name'  => 'issueInvoice',
            'label' => 'order.label.issue_invoice',
        ]));
        
        $this->addClientDetailsFieldset($form);
        $this->addContactDetailsFieldset($form);
        $this->addBillingAddressFieldset($form);
        $this->addShippingAddressFieldset($form);
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    protected function addBillingAddressFieldset(FormInterface $form)
    {
        $billingAddress = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'billingAddress',
            'label' => 'client.heading.billing_address',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.firstName',
            'label' => 'client.label.contact_details.first_name',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.lastName',
            'label' => 'client.label.contact_details.last_name',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.companyName',
            'label' => 'client.label.address.company_name',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.vatId',
            'label' => 'client.label.address.vat_id',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'    => 'billingAddress.line1',
            'label'   => 'client.label.address.line1',
            'comment' => 'client.label.address.line1',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'    => 'billingAddress.line2',
            'label'   => 'client.label.address.line2',
            'comment' => 'client.label.address.line2',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.postalCode',
            'label' => 'client.label.address.postal_code',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.state',
            'label' => 'client.label.address.state',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.city',
            'label' => 'client.label.address.city',
        ]));
        
        $billingAddress->addChild($this->getElement('select', [
            'name'    => 'billingAddress.country',
            'label'   => 'client.label.address.country',
            'options' => $this->get('country.repository')->all(),
            'default' => $this->getShopStorage()->getCurrentShop()->getDefaultCountry(),
        ]));
    }
    
    protected function addShippingAddressFieldset(FormInterface $form)
    {
        $shippingAddress = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'shippingAddress',
            'label' => 'client.heading.shipping_address',
        ]));
        
        $shippingAddress->addChild($this->getElement('checkbox', [
            'name'  => 'shippingAddress.copyBillingAddress',
            'label' => 'client.label.address.copy_address',
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
            'name'  => 'shippingAddress.companyName',
            'label' => 'client.label.address.company_name',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'    => 'shippingAddress.line1',
            'label'   => 'client.label.address.line1',
            'comment' => 'client.label.address.line1',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'    => 'shippingAddress.line2',
            'label'   => 'client.label.address.line2',
            'comment' => 'client.label.address.line2',
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
            'options' => $this->get('country.repository')->all(),
            'default' => $this->getShopStorage()->getCurrentShop()->getDefaultCountry(),
        ]));
    }
    
    protected function addContactDetailsFieldset(FormInterface $form)
    {
        $contactDetails = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'contactDetails',
            'label' => 'client.heading.contact_details',
        ]));
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.firstName',
            'label' => 'client.label.address.first_name',
        ]));
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.lastName',
            'label' => 'client.label.address.last_name',
        ]));
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.phone',
            'label' => 'common.label.phone',
        ]));
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.secondaryPhone',
            'label' => 'common.label.secondary_phone',
        ]));
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.email',
            'label' => 'common.label.email',
        ]));
    }
    
    protected function addClientDetailsFieldset(FormInterface $form)
    {
        if (!$this->getSecurityHelper()->getCurrentClient() instanceof Client) {
            $clientDetails = $form->addChild($this->getElement('nested_fieldset', [
                'name'  => 'clientDetails',
                'label' => 'client.heading.client',
            ]));
            
            $clientDetails->addChild($this->getElement('checkbox', [
                'name'  => 'clientDetails.createAccount',
                'label' => 'client.label.create_account',
            ]));
            
            $clientDetails->addChild($this->getElement('text_field', [
                'name'  => 'clientDetails.username',
                'label' => 'client.label.username',
            ]));
            
            $clientDetails->addChild($this->getElement('password', [
                'name'  => 'clientDetails.hashedPassword',
                'label' => 'client.label.password',
            ]));
            
            $clientDetails->addChild($this->getElement('password', [
                'name'  => 'clientDetails.passwordConfirm',
                'label' => 'client.label.confirm_password',
            ]));
            
            $clientDetails->addChild($this->getElement('checkbox', [
                'name'  => 'clientDetails.conditionsAccepted',
                'label' => 'client.label.conditions_accepted',
            ]));
        }
    }
}
