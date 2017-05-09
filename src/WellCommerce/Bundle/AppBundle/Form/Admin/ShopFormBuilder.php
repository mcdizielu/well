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
 * Class ShopFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ShopFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.shop';
    }
    
    public function buildForm(FormInterface $form)
    {
        $currencies = $this->get('currency.dataset.admin')->getResult('select', ['order_by' => 'code'], [
            'label_column' => 'code',
            'value_column' => 'code',
        ]);
        
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $requiredData->addChild($this->getElement('select', [
            'name'        => 'company',
            'label'       => 'shop.label.company',
            'options'     => $this->get('company.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('company.repository')),
        ]));
        
        $requiredData->addChild($this->getElement('select', [
            'name'        => 'theme',
            'label'       => 'shop.label.theme',
            'options'     => $this->get('theme.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('theme.repository')),
        ]));
        
        $urlData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'url_data',
            'label' => 'shop.fieldset.url_configuration',
        ]));
        
        $urlData->addChild($this->getElement('text_field', [
            'name'  => 'url',
            'label' => 'shop.label.url',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $cartSettings = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'cart_settings',
            'label' => 'shop.fieldset.cart_configuration',
        ]));
        
        $cartSettings->addChild($this->getElement('select', [
            'name'    => 'defaultCountry',
            'label'   => 'shop.label.default_country',
            'options' => $this->get('country.repository')->all(),
        ]));
        
        $cartSettings->addChild($this->getElement('select', [
            'name'    => 'defaultCurrency',
            'label'   => 'shop.label.default_currency',
            'options' => $currencies,
        ]));
        
        $cartSettings->addChild($this->getElement('select', [
            'name'        => 'clientGroup',
            'label'       => 'shop.label.default_client_group',
            'options'     => $this->get('client_group.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('client_group.repository')),
        ]));
        
        $cartSettings->addChild($this->getElement('checkbox', [
            'name'    => 'enableClient',
            'label'   => 'shop.label.enable_client',
            'comment' => 'shop.comment.enable_client',
        ]));
        
        $addressData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'address_data',
            'label' => 'company.label.addresses',
        ]));
        
        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'line1',
            'label' => 'common.label.line1',
        ]));
        
        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'line2',
            'label' => 'common.label.line2',
        ]));
        
        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'state',
            'label' => 'common.label.state',
        ]));
        
        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'postalCode',
            'label' => 'common.label.postal_code',
        ]));
        
        $addressData->addChild($this->getElement('text_field', [
            'name'  => 'city',
            'label' => 'common.label.city',
        ]));
        
        $addressData->addChild($this->getElement('select', [
            'name'    => 'country',
            'label'   => 'common.label.country',
            'options' => $this->get('country.repository')->all(),
        ]));
        
        $mailerConfiguration = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'mailer_configuration',
            'label' => 'shop.fieldset.mailer_configuration',
        ]));
        
        $mailerConfiguration->addChild($this->getElement('text_field', [
            'name'  => 'mailerConfiguration.from',
            'label' => 'shop.label.mailer_configuration.from',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $mailerConfiguration->addChild($this->getElement('text_field', [
            'name'  => 'mailerConfiguration.host',
            'label' => 'shop.label.mailer_configuration.host',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $mailerConfiguration->addChild($this->getElement('text_field', [
            'name'  => 'mailerConfiguration.port',
            'label' => 'shop.label.mailer_configuration.port',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $mailerConfiguration->addChild($this->getElement('select', [
            'name'    => 'mailerConfiguration.encrypt',
            'label'   => 'shop.label.mailer_configuration.encrypt',
            'options' => [
                ''    => '---',
                'tls' => 'tls',
                'ssl' => 'ssl',
            ],
        ]));
        
        $mailerConfiguration->addChild($this->getElement('text_field', [
            'name'  => 'mailerConfiguration.user',
            'label' => 'shop.label.mailer_configuration.user',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $mailerConfiguration->addChild($this->getElement('password', [
            'name'  => 'mailerConfiguration.pass',
            'label' => 'shop.label.mailer_configuration.pass',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $mailerConfiguration->addChild($this->getElement('password', [
            'name'  => 'mailerConfiguration.pass',
            'label' => 'shop.label.mailer_configuration.pass',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $mailerConfiguration->addChild($this->getElement('text_field', [
            'name'  => 'mailerConfiguration.bcc',
            'label' => 'shop.label.mailer_configuration.bcc',
        ]));
        
        $minimumOrderAmount = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'minimumOrderAmount',
            'label' => 'common.fieldset.minimum_order_amount',
        ]));
        
        $minimumOrderAmount->addChild($this->getElement('text_field', [
            'name'    => 'minimumOrderAmount.value',
            'label'   => 'common.label.minimum_order_amount.value',
            'suffix'  => '%',
            'filters' => [
                $this->getFilter('comma_to_dot_changer'),
            ],
            'rules'   => [
                $this->getRule('required'),
            ],
            'default' => 0,
        ]));
        
        $minimumOrderAmount->addChild($this->getElement('select', [
            'name'    => 'minimumOrderAmount.currency',
            'label'   => 'common.label.minimum_order_amount.currency',
            'options' => $this->get('currency.dataset.admin')->getResult('select', ['order_by' => 'code'], [
                'label_column' => 'code',
                'value_column' => 'code',
            ]),
        ]));
        
        $this->addMetadataFieldset($form, $this->get('shop.repository'));
        
        $mediaData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'logo_data',
            'label' => 'shop.fieldset.logo',
        ]));
        
        $mediaData->addChild($this->getElement('local_file', [
            'name'         => 'logo',
            'label'        => 'local_file.label.selected',
            'repeat_min'   => 0,
            'repeat_max'   => 1,
            'session_id'   => $this->getRequestHelper()->getSessionId(),
            'session_name' => $this->getRequestHelper()->getSessionName(),
            'file_source'  => 'web/themes/',
            'file_types'   => ['png', 'jpg', 'jpeg'],
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
