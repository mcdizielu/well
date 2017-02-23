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

namespace WellCommerce\Bundle\AppBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\CoreBundle\Doctrine\Fixtures\AbstractDataFixture;
use WellCommerce\Bundle\OrderBundle\Entity\PaymentMethod;

/**
 * Class LoadPaymentData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadPaymentMethodData extends AbstractDataFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }

        $faker           = $this->getFakerGenerator();
        $shippingMethods = new ArrayCollection();
        $shippingMethods->add($this->getReference('shipping_method_fedex'));
        $shippingMethods->add($this->getReference('shipping_method_ups'));
        
        $cod = new PaymentMethod();
        $cod->setEnabled(1);
        $cod->setHierarchy(10);
        $cod->setProcessor('cash_on_delivery');
        foreach($this->getLocales() as $locale){
            $cod->translate($locale->getCode())->setName('Cash on delivery');
        }
        $cod->setShippingMethods($shippingMethods);
        $cod->setPaymentPendingOrderStatus($this->getReference('order_status_pending_payment'));
        $cod->setPaymentFailureOrderStatus($this->getReference('order_status_payment_failed'));
        $cod->setPaymentSuccessOrderStatus($this->getReference('order_status_paid'));
        $cod->setConfiguration([]);
        $cod->mergeNewTranslations();
        $manager->persist($cod);
        
        $bankTransfer = new PaymentMethod();
        $bankTransfer->setEnabled(1);
        $bankTransfer->setHierarchy(20);
        $bankTransfer->setProcessor('bank_transfer');
        foreach($this->getLocales() as $locale){
            $bankTransfer->translate($locale->getCode())->setName('Bank transfer');
        }
        $bankTransfer->setShippingMethods($shippingMethods);
        $bankTransfer->setPaymentPendingOrderStatus($this->getReference('order_status_pending_payment'));
        $bankTransfer->setPaymentFailureOrderStatus($this->getReference('order_status_payment_failed'));
        $bankTransfer->setPaymentSuccessOrderStatus($this->getReference('order_status_paid'));
        $bankTransfer->setConfiguration([
            'bank_transfer_account_number' => '1111 2222 3333 4444 5555 6666',
            'bank_transfer_account_owner'  => 'WellCommerce',
            'bank_transfer_sort_number'    => 'SORTCODE',
        ]);
        $bankTransfer->mergeNewTranslations();
        $manager->persist($bankTransfer);
        
        $manager->flush();
        
        $this->setReference('payment_method_cod', $cod);
        $this->setReference('payment_method_bank_transfer', $bankTransfer);
    }
}
