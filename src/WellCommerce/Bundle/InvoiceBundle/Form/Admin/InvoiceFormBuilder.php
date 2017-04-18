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

namespace WellCommerce\Bundle\InvoiceBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\DataTransformer\DateTransformer;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class InvoiceFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class InvoiceFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.invoice';
    }
    
    public function buildForm(FormInterface $form)
    {
        $countries = $this->get('country.repository')->all();
        
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'amountDue',
            'label' => 'invoice.label.amount_due',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'amountPaid',
            'label' => 'invoice.label.amount_paid',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
    
        $requiredData->addChild($this->getElement('checkbox', [
            'name'  => 'paid',
            'label' => 'invoice.label.paid',
        ]));
        
        $requiredData->addChild($this->getElement('date', [
            'name'        => 'date',
            'label'       => 'invoice.label.date',
            'transformer' => new DateTransformer('Y-m-d'),
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $requiredData->addChild($this->getElement('date', [
            'name'        => 'dueDate',
            'label'       => 'invoice.label.due_date',
            'transformer' => new DateTransformer('Y-m-d'),
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'amountPaid',
            'label' => 'invoice.label.amount_paid',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'shippingMethodName',
            'label' => 'invoice.label.shipping_method_name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'paymentMethodName',
            'label' => 'invoice.label.payment_method_name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
    
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'signature',
            'label' => 'invoice.label.signature',
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
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.lastName',
            'label' => 'client.label.address.last_name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.line1',
            'label' => 'client.label.address.line1',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.line2',
            'label' => 'client.label.address.line2',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.postalCode',
            'label' => 'client.label.address.postal_code',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.state',
            'label' => 'client.label.address.state',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.city',
            'label' => 'client.label.address.city',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $billingAddress->addChild($this->getElement('select', [
            'name'    => 'billingAddress.country',
            'label'   => 'client.label.address.country',
            'options' => $countries,
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.vatId',
            'label' => 'client.label.address.vat_id',
        ]));
        
        $billingAddress->addChild($this->getElement('text_field', [
            'name'  => 'billingAddress.companyName',
            'label' => 'client.label.address.company_name',
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
