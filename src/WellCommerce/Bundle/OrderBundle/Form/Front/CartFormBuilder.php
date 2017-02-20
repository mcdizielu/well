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

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Bundle\OrderBundle\Context\OrderContext;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\PaymentMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethodCost;
use WellCommerce\Bundle\OrderBundle\Provider\Front\OrderProviderInterface;
use WellCommerce\Bundle\OrderBundle\Provider\ShippingMethodOptionsProviderInterface;
use WellCommerce\Bundle\OrderBundle\Provider\ShippingMethodProviderInterface;
use WellCommerce\Component\Form\Elements\ElementInterface;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\Elements\Optioned\RadioGroup;

/**
 * Class CartFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CartFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'front.cart';
    }
    
    public function buildForm(FormInterface $form)
    {
        $order = $this->getOrderProvider()->getCurrentOrder();
        
        $shippingAddress = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'shippingAddress',
            'label' => $this->trans('client.heading.shipping_address'),
        ]));
        
        $shippingAddress->addChild($this->getElement('select', [
            'name'    => 'shippingAddress.country',
            'label'   => $this->trans('client.label.address.country'),
            'options' => $this->get('country.repository')->all(),
            'default' => $this->getShopStorage()->getCurrentShop()->getDefaultCountry(),
        ]));
        
        $shippingMethods = $form->addChild($this->getElement('radio_group', [
            'name'        => 'shippingMethod',
            'label'       => $this->trans('order.label.shipping_method'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('shipping_method.repository')),
        ]));
        
        $this->addShippingMethods($order, $shippingMethods);
        
        $this->addShippingOptions($order, $form);
        
        $paymentMethods = $form->addChild($this->getElement('radio_group', [
            'name'        => 'paymentMethod',
            'label'       => $this->trans('order.label.payment_method'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('payment_method.repository')),
        ]));
        
        $this->addPaymentMethods($order, $paymentMethods);
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    /**
     * Adds shipping options if available for order's shipping method
     *
     * @param Order         $order
     * @param FormInterface $form
     */
    private function addShippingOptions(Order $order, FormInterface $form)
    {
        if ($order->getShippingMethod() instanceof ShippingMethod) {
            $provider = $this->getOptionsProvider($order->getShippingMethod());
            if ($provider instanceof ShippingMethodOptionsProviderInterface) {
                $form->addChild($this->getElement('select', [
                    'name'    => 'shippingMethodOption',
                    'label'   => $this->trans('order.label.shipping_method'),
                    'options' => $provider->getShippingOptions(),
                ]));
            }
        }
    }
    
    /**
     * Adds shipping method options to select
     *
     * @param Order                       $order
     * @param ElementInterface|RadioGroup $radioGroup
     */
    private function addShippingMethods(Order $order, ElementInterface $radioGroup)
    {
        $collection = $this->getShippingMethodProvider()->getCosts(new OrderContext($order));
        
        $collection->map(function (ShippingMethodCost $shippingMethodCost) use ($radioGroup) {
            $shippingMethod = $shippingMethodCost->getShippingMethod();
            $baseCurrency   = $shippingMethod->getCurrency()->getCode();
            $grossAmount    = $shippingMethodCost->getCost()->getGrossAmount();
            
            $label = [
                'name'    => $shippingMethod->translate()->getName(),
                'comment' => $this->getCurrencyHelper()->convertAndFormat($grossAmount, $baseCurrency),
            ];
            
            $radioGroup->addOptionToSelect($shippingMethod->getId(), $label);
        });
    }
    
    /**
     * Adds payment method options to select
     *
     * @param Order                       $order
     * @param ElementInterface|RadioGroup $radioGroup
     */
    private function addPaymentMethods(Order $order, ElementInterface $radioGroup)
    {
        $order = $this->getOrderProvider()->getCurrentOrder();
        
        if ($order->getShippingMethod() instanceof ShippingMethod) {
            $collection = $order->getShippingMethod()->getPaymentMethods();
            
            $collection->map(function (PaymentMethod $paymentMethod) use ($radioGroup) {
                if ($paymentMethod->isEnabled()) {
                    $radioGroup->addOptionToSelect($paymentMethod->getId(), $paymentMethod->translate()->getName());
                }
            });
        }
    }
    
    private function getShippingMethodProvider(): ShippingMethodProviderInterface
    {
        return $this->get('shipping_method.provider');
    }
    
    private function getOrderProvider(): OrderProviderInterface
    {
        return $this->get('order.provider.front');
    }
    
    private function getOptionsProvider(ShippingMethod $method)
    {
        $provider   = $method->getOptionsProvider();
        $collection = $this->get('shipping_method.options_provider.collection');
        
        if ($collection->containsKey($provider)) {
            return $collection->get($provider);
        }
        
        return null;
    }
}
