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

namespace WellCommerce\Bundle\StandardEditionBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\StandardEditionBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadShopData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadShopData extends AbstractDataFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        /**
         * @var $theme       \WellCommerce\Bundle\AppBundle\Entity\Theme
         * @var $company     \WellCommerce\Bundle\AppBundle\Entity\Company
         * @var $orderStatus \WellCommerce\Bundle\OrderBundle\Entity\OrderStatus
         */
        $theme    = $this->getReference('theme');
        $company  = $this->getReference('company');
        $currency = $this->randomizeSamples('currency', LoadCurrencyData::$samples);
        
        $shop = new Shop();
        $shop->setName('WellCommerce');
        $shop->setCompany($company);
        $shop->setTheme($theme);
        $shop->setUrl('localhost');
        $shop->setDefaultCountry('US');
        $shop->setDefaultCurrency($currency->getCode());
        $shop->setClientGroup($this->getReference('client_group'));
        $manager->persist($shop);
        $manager->flush();
        
        $this->get('shop.storage')->setCurrentShop($shop);
        $this->setReference('shop', $shop);
    }
}
