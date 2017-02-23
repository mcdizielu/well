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

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Bundle\CouponBundle\Entity\Coupon;
use WellCommerce\Bundle\CouponBundle\Helper\CouponHelper;
use WellCommerce\Bundle\OrderBundle\Context\OrderContext;
use WellCommerce\Bundle\OrderBundle\Entity\OrderModifier;
use WellCommerce\Bundle\OrderBundle\Entity\PaymentMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethodCost;
use WellCommerce\Bundle\OrderBundle\Provider\Admin\OrderProviderInterface;
use WellCommerce\Bundle\OrderBundle\Provider\ShippingMethodProviderInterface;
use WellCommerce\Component\Form\Elements\ElementInterface;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\Elements\Optioned\Select;

/**
 * Class OrderFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.order';
    }
    
    public function buildForm(FormInterface $form)
    {
        $order     = $this->getOrderProvider()->getCurrentOrder();
        $countries = $this->get('country.repository')->all();
        
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'order.form.fieldset.products',
        ]));
        
        $requiredData->addChild($this->getElement('order_editor', [
            'name'                => 'products',
            'label'               => 'order.heading.products',
            'repeat_min'          => 1,
            'repeat_max'          => ElementInterface::INFINITE,
            'load_products_route' => 'admin.product.grid',
            'on_change'           => 'OnProductListChange',
            'on_before_change'    => 'OnProductListBeforeChange',
            'transformer'         => $this->getRepositoryTransformer('order_product_collection', $this->get('order_product.repository')),
        ]));
        
        $contactDetails = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'contactDetails',
            'label' => 'order.heading.contact_details',
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
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.secondaryPhone',
            'label' => 'client.label.contact_details.secondary_phone',
        ]));
        
        $contactDetails->addChild($this->getElement('text_field', [
            'name'  => 'contactDetails.email',
            'label' => 'client.label.contact_details.email',
        ]));
        
        $addresses = $form->addChild($this->getElement('columns', [
            'name'  => 'addresses',
            'label' => 'order.heading.address',
        ]));
        
        $billingAddress = $addresses->addChild($this->getElement('nested_fieldset', [
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
        
        $shippingAddress = $addresses->addChild($this->getElement('nested_fieldset', [
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
            'name'  => 'shippingAddress.companyName',
            'label' => 'client.label.address.company_name',
        ]));
        
        $shippingAddress->addChild($this->getElement('text_field', [
            'name'  => 'shippingAddress.line1',
            'label' => 'client.label.address.line1',
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
        ]));
        
        $orderDetails = $form->addChild($this->getElement('columns', [
            'name'  => 'orderMethodsDetails',
            'label' => 'order.heading.order_methods_details',
        ]));
        
        $paymentShippingData = $orderDetails->addChild($this->getElement('nested_fieldset', [
            'name'  => 'methods',
            'label' => 'order.heading.methods',
        ]));
        
        $shippingMethod = $paymentShippingData->addChild($this->getElement('select', [
            'name'        => 'shippingMethod',
            'label'       => 'order.label.shipping_method',
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('shipping_method.repository')),
        ]));
        
        $this->addShippingOptions($shippingMethod);
        
        $paymentMethod = $paymentShippingData->addChild($this->getElement('select', [
            'name'        => 'paymentMethod',
            'label'       => 'order.label.payment_method',
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('payment_method.repository')),
        ]));
        
        $this->addPaymentOptions($paymentMethod);
        
        $order->getModifiers()->map(function (OrderModifier $modifier) use ($paymentShippingData) {
            $paymentShippingData->addChild($this->getElement('constant', [
                'name'  => 'summary.' . $modifier->getName(),
                'label' => $this->trans($modifier->getDescription()),
            ]))->setValue($modifier->getGrossAmount());
        });
        
        if ($order->getCoupon() instanceof Coupon) {
            $paymentShippingData->addChild($this->getElement('constant', [
                'name'  => 'coupon.code',
                'label' => 'order.label.coupon.code',
            ]))->setValue($order->getCoupon()->getCode());
            
            $paymentShippingData->addChild($this->getElement('constant', [
                'name'  => 'coupon.modifier',
                'label' => 'order.label.coupon.modifier',
            ]))->setValue(CouponHelper::formatModifier($order->getCoupon()));
        }
        
        $summaryData = $orderDetails->addChild($this->getElement('nested_fieldset', [
            'name'  => 'summary',
            'label' => 'order.heading.order_total',
        ]));
        
        $summaryData->addChild($this->getElement('constant', [
            'name'  => 'productTotal.netPrice',
            'label' => 'order.label.product_total.net_price',
        ]));
        
        $summaryData->addChild($this->getElement('constant', [
            'name'  => 'productTotal.taxAmount',
            'label' => 'order.label.product_total.tax_amount',
        ]));
        
        $summaryData->addChild($this->getElement('constant', [
            'name'  => 'productTotal.grossPrice',
            'label' => 'order.label.product_total.gross_price',
        ]));
        
        $summaryData->addChild($this->getElement('constant', [
            'name'  => 'summary.netAmount',
            'label' => 'order.label.summary.net_amount',
        ]));
        
        $summaryData->addChild($this->getElement('constant', [
            'name'  => 'summary.taxAmount',
            'label' => 'order.label.summary.tax_amount',
        ]));
        
        $summaryData->addChild($this->getElement('constant', [
            'name'  => 'summary.grossAmount',
            'label' => 'order.label.summary.gross_amount',
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    private function getOrderProvider(): OrderProviderInterface
    {
        return $this->get('order.provider.admin');
    }
    
    private function getShippingMethodProvider(): ShippingMethodProviderInterface
    {
        return $this->get('shipping_method.provider');
    }
    
    /**
     * Adds shipping method options to select
     *
     * @param ElementInterface|Select $radioGroup
     */
    private function addShippingOptions(ElementInterface $radioGroup)
    {
        $order      = $this->getOrderProvider()->getCurrentOrder();
        $collection = $this->getShippingMethodProvider()->getCosts(new OrderContext($order));
        
        $collection->map(function (ShippingMethodCost $shippingMethodCost) use ($radioGroup) {
            $shippingMethod = $shippingMethodCost->getShippingMethod();
            $baseCurrency   = $shippingMethod->getCurrency()->getCode();
            $grossAmount    = $shippingMethodCost->getCost()->getGrossAmount();
            
            $label = sprintf(
                '%s (%s)',
                $shippingMethod->translate()->getName(),
                $this->getCurrencyHelper()->convertAndFormat($grossAmount, $baseCurrency)
            );
            
            $radioGroup->addOptionToSelect($shippingMethod->getId(), $label);
        });
    }
    
    /**
     * Adds payment method options to select
     *
     * @param ElementInterface|Select $radioGroup
     */
    private function addPaymentOptions(ElementInterface $radioGroup)
    {
        $order          = $this->getOrderProvider()->getCurrentOrder();
        $shippingMethod = $order->getShippingMethod();
        if ($shippingMethod instanceof ShippingMethod) {
            $collection = $shippingMethod->getPaymentMethods();
            
            $collection->map(function (PaymentMethod $paymentMethod) use ($radioGroup) {
                $radioGroup->addOptionToSelect($paymentMethod->getId(), $paymentMethod->translate()->getName());
            });
        }
    }
}
