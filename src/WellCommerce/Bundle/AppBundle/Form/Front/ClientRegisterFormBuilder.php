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
namespace WellCommerce\Bundle\AppBundle\Form\Front;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ClientRegisterFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientRegisterFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'front.client_register';
    }
    
    public function buildForm(FormInterface $form)
    {
        $contactDetails = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'contactDetails',
            'label' => 'client.heading.contact_details',
        ]));
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.firstName',
            'label' => 'client.label.contact_details.first_name',
        ]));
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.lastName',
            'label' => 'client.label.contact_details.last_name',
        ]));
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.phone',
            'label' => 'client.label.contact_details.phone',
        ]));
        
        $clientDetails = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'clientDetails',
            'label' => 'client.heading.client',
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
            'name'    => 'clientDetails.conditionsAccepted',
            'label'   => 'client.label.accept_conditions',
            'default' => false,
            'comment' => 'client.label.accept_conditions'
        ]));
        
        $clientDetails->addChild($this->getElement('checkbox', [
            'name'    => 'clientDetails.newsletterAccepted',
            'label'   => 'client.label.accept_newsletter',
            'comment' => 'client.label.accept_newsletter',
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
