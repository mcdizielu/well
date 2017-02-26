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

use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\AppBundle\DataFixtures\AbstractDataFixture;
use WellCommerce\Bundle\CoreBundle\Entity\MailerConfiguration;

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
        $shop->setUrl($this->container->getParameter('fallback_hostname'));
        $shop->setDefaultCountry('US');
        $shop->setDefaultCurrency($currency->getCode());
        $shop->setClientGroup($this->getReference('client_group'));
        
        $mailerConfiguration = new MailerConfiguration();
        $mailerConfiguration->setFrom($this->container->getParameter('mailer_from'));
        $mailerConfiguration->setHost($this->container->getParameter('mailer_host'));
        $mailerConfiguration->setPort($this->container->getParameter('mailer_port'));
        $mailerConfiguration->setUser($this->container->getParameter('mailer_user'));
        $mailerConfiguration->setPass($this->container->getParameter('mailer_password'));
        $mailerConfiguration->setBcc($this->container->getParameter('mailer_from'));
        
        $shop->setMailerConfiguration($mailerConfiguration);
        
        $manager->persist($shop);
        $manager->flush();
        
        $this->get('shop.storage')->setCurrentShop($shop);
        $this->setReference('shop', $shop);
    }
}
