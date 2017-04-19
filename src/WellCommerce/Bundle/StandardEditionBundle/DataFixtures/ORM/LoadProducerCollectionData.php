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
use WellCommerce\Bundle\StandardEditionBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadProducerData
 *
 * @author  Rafał Martonik <rafal@wellcommerce.org>
 */
class LoadProducerCollectionData extends AbstractDataFixture
{
    public static $samples = [];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }

        $this->createLayoutBoxes($manager, [
            'producer_collection_menu'     => [
                'type' => 'ProducerCollectionMenu',
                'name' => 'Producer collections',
            ],
            'producer_collection_products' => [
                'type' => 'ProducerCollectionProducts',
                'name' => 'Producer collection products',
            ],
        ]);

        $manager->flush();
    }
}
