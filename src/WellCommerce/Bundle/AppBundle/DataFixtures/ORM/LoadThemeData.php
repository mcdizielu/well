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
use WellCommerce\Bundle\AppBundle\Entity\Theme;
use WellCommerce\Bundle\CoreBundle\Doctrine\Fixtures\AbstractDataFixture;

/**
 * Class LoadThemeData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class LoadThemeData extends AbstractDataFixture
{
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }

        $theme = new Theme();
        $theme->setName('WellCommerce Default Theme');
        $theme->setFolder('wellcommerce-default-theme');

        $manager->persist($theme);
        $manager->flush();

        $this->setReference('theme', $theme);
    }
}
