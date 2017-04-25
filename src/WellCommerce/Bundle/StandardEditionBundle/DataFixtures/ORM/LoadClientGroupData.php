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
use WellCommerce\Bundle\AppBundle\Entity\ClientGroup;
use WellCommerce\Bundle\StandardEditionBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadClientGroupData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadClientGroupData extends AbstractDataFixture
{
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $currency    = $this->randomizeSamples('currency', LoadCurrencyData::$samples);
        $clientGroup = new ClientGroup();
        $clientGroup->setDiscount(10);
        foreach ($this->getLocales() as $locale) {
            $clientGroup->translate($locale->getCode())->setName('Default client group');
        }
        
        $clientGroup->mergeNewTranslations();
        
        $clientGroup->getMinimumOrderAmount()->setValue(0);
        $clientGroup->getMinimumOrderAmount()->setCurrency($currency->getCode());
        
        $manager->persist($clientGroup);
        $manager->flush();
        
        $this->setReference('client_group', $clientGroup);
    }
}
