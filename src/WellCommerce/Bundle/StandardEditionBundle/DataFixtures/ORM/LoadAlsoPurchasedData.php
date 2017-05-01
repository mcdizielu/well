<?php
/**
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
 * Class LoadAlsoPurchasedData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class LoadAlsoPurchasedData extends AbstractDataFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->createLayoutBoxes($manager, [
            'also_purchased' => [
                'type'     => 'AlsoPurchased',
                'name'     => 'Also purchased products',
                'settings' => [
                    'limit' => 4,
                ],
            ],
        ]);
        
        $manager->flush();
    }
}
