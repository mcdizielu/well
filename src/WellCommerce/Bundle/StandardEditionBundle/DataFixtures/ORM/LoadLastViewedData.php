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
 * Class LoadLastViewedData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class LoadLastViewedData extends AbstractDataFixture
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
            'last_viewed' => [
                'type'     => 'LastViewed',
                'name'     => 'Last viewed products',
                'settings' => [
                    'limit' => 4,
                ],
            ],
        ]);
        
        $manager->flush();
    }
}
