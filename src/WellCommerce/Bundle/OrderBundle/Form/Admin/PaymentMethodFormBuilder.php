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
namespace WellCommerce\Bundle\OrderBundle\Form\Admin;

use Symfony\Component\PropertyAccess\PropertyPath;
use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Bundle\OrderBundle\Processor\PaymentProcessorCollection;
use WellCommerce\Component\Form\Conditions\Equals;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class PaymentMethodFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PaymentMethodFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.payment_method';
    }
    
    public function buildForm(FormInterface $form)
    {
        $orderStatuses = $this->get('order_status.dataset.admin')->getResult('select');
        $options       = [];
        $processors    = $this->getPaymentProcessorCollection();
        $processorKeys = $processors->keys();
        foreach ($processors->all() as $processor) {
            $processorName           = $processor->getConfigurator()->getName();
            $options[$processorName] = $processorName;
        }
        
        $defaultProcessor = reset($processorKeys);
        
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('payment_method.repository')),
        ]));
        
        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $processorType = $requiredData->addChild($this->getElement('select', [
            'name'    => 'processor',
            'label'   => 'payment_method.label.processor',
            'options' => $options,
            'default' => $defaultProcessor,
        ]));
        
        $requiredData->addChild($this->getElement('checkbox', [
            'name'  => 'enabled',
            'label' => 'common.label.enabled',
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'hierarchy',
            'label' => 'common.label.hierarchy',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $clientGroupData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'client_group_data',
            'label' => 'payment_method.fieldset.client_groups',
        ]));
        
        $clientGroupData->addChild($this->getElement('tip', [
            'tip' => 'payment_method.tip.client_groups',
        ]));
        
        $clientGroupData->addChild($this->getElement('multi_select', [
            'name'        => 'clientGroups',
            'label'       => 'payment_method.label.client_groups',
            'options'     => $this->get('client_group.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('client_group.repository')),
        ]));
        
        $statusesData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'statuses',
            'label' => 'payment_method.fieldset.order_statuses',
        ]));
        
        $statusesData->addChild($this->getElement('select', [
            'name'        => 'paymentPendingOrderStatus',
            'label'       => 'payment_method.label.payment_pending_order_status',
            'options'     => $orderStatuses,
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('order_status.repository')),
        ]));
        
        $statusesData->addChild($this->getElement('select', [
            'name'        => 'paymentSuccessOrderStatus',
            'label'       => 'payment_method.label.payment_success_order_status',
            'options'     => $orderStatuses,
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('order_status.repository')),
        ]));
        
        $statusesData->addChild($this->getElement('select', [
            'name'        => 'paymentFailureOrderStatus',
            'label'       => 'payment_method.label.payment_failure_order_status',
            'options'     => $orderStatuses,
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('order_status.repository')),
        ]));
        
        $shippingMethodsData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'shipping_methods_data',
            'label' => 'payment_method.fieldset.shipping_methods',
        ]));
        
        $shippingMethodsData->addChild($this->getElement('multi_select', [
            'name'        => 'shippingMethods',
            'label'       => 'payment_method.label.shipping_methods',
            'options'     => $this->get('shipping_method.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('shipping_method.repository')),
        ]));
        
        $configurationData = $form->addChild($this->getElement('nested_fieldset', [
            'name'          => 'configuration',
            'property_path' => new PropertyPath('configuration'),
            'label'         => 'payment_method.fieldset.processor_configuration',
        ]));
        
        foreach ($processors->all() as $processor) {
            
            $dependency = $this->getDependency('show', [
                'form'      => $form,
                'field'     => $processorType,
                'condition' => new Equals($processor->getConfigurator()->getName()),
            ]);
            
            $processor->getConfigurator()->addConfigurationFields($this, $configurationData, $dependency);
        }
        
        $this->addShopsFieldset($form);
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    protected function getPaymentProcessorCollection(): PaymentProcessorCollection
    {
        return $this->container->get('payment.processor.collection');
    }
}
