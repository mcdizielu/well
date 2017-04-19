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
 * Class LoadShowcaseData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class LoadShowcaseData extends AbstractDataFixture
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
            'showcase' => [
                'type'     => 'Showcase',
                'name'     => 'Showcase',
                'settings' => [
                    'status'     => $this->getReference('product_status_featured')->getId(),
                    'limit'      => 10,
                    'categories' => [],
                ],
            ],
        ]);
        
        $manager->flush();
    }
}
