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
use WellCommerce\Bundle\AppBundle\Entity\Price;
use WellCommerce\Bundle\CoreBundle\Doctrine\Fixtures\AbstractDataFixture;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethodCost;

/**
 * Class LoadShippingData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadShippingMethodData extends AbstractDataFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $tax      = $this->randomizeSamples('tax', LoadTaxData::$samples);
        $currency = $this->randomizeSamples('currency', LoadCurrencyData::$samples);
        $shops    = new ArrayCollection([$this->getReference('shop')]);
        
        $fedEx = new ShippingMethod();
        $fedEx->setCalculator('price_table');
        $fedEx->setTax($tax);
        $fedEx->setCurrency($currency);
        foreach ($this->getLocales() as $locale) {
            $fedEx->translate($locale->getCode())->setName('FedEx');
        }
        $fedEx->mergeNewTranslations();
        $fedEx->setCosts($this->getShippingCostsCollection($fedEx));
        $fedEx->setShops($shops);
        $manager->persist($fedEx);
        
        $ups = new ShippingMethod();
        $ups->setCalculator('price_table');
        $ups->setTax($tax);
        $ups->setCurrency($currency);
        foreach ($this->getLocales() as $locale) {
            $ups->translate($locale->getCode())->setName('UPS');
        }
        $ups->mergeNewTranslations();
        $ups->setCosts($this->getShippingCostsCollection($ups));
        $ups->setShops($shops);
        $manager->persist($ups);
        
        $manager->flush();
        
        $this->setReference('shipping_method_fedex', $fedEx);
        $this->setReference('shipping_method_ups', $ups);
    }
    
    private function getShippingCostsCollection(ShippingMethod $shippingMethod)
    {
        $collection = new ArrayCollection();
        
        $cost = new ShippingMethodCost();
        $cost->setRangeFrom(0);
        $cost->setRangeTo(100000);
        
        $price = new Price();
        $price->setCurrency('EUR');
        $price->setNetAmount(10);
        $price->setTaxAmount(2.3);
        $price->setTaxRate(23);
        $price->setGrossAmount(12.3);
        
        $cost->setCost($price);
        $cost->setShippingMethod($shippingMethod);
        $collection->add($cost);
        
        return $collection;
    }
}
