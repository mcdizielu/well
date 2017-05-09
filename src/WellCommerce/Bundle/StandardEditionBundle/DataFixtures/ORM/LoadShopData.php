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
         */
        $theme          = $this->getReference('theme');
        $company        = $this->getReference('company');
        $currency       = $this->randomizeSamples('currency', LoadCurrencyData::$samples);
        
        $shop = new Shop();
        $shop->setName('WellCommerce');
        $shop->setCompany($company);
        $shop->setTheme($theme);
        $shop->setUrl('localhost');
        $shop->setDefaultCountry('US');
        $shop->setDefaultCurrency($currency->getCode());
        $shop->setClientGroup($this->getReference('client_group'));
        $shop->getMinimumOrderAmount()->setCurrency($currency->getCode());
        $shop->getMinimumOrderAmount()->setValue(0);
        $shop->setEnableClient(true);
        
        foreach ($this->getLocales() as $locale) {
            $shop->translate($locale->getCode())->getMeta()->setTitle('WellCommerce');
            $shop->translate($locale->getCode())->getMeta()->setKeywords('e-commerce, open-source, symfony, framework, shop');
            $shop->translate($locale->getCode())->getMeta()->setDescription('Modern e-commerce engine built on top of Symfony 3 full-stack framework');
        }
        
        $shop->mergeNewTranslations();
        $manager->persist($shop);
        $manager->flush();
        
        $this->get('shop.storage')->setCurrentShop($shop);
        $this->setReference('shop', $shop);
    }
}
