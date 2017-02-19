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

namespace WellCommerce\Bundle\CatalogBundle\Tests\Entity;

use WellCommerce\Bundle\CatalogBundle\Entity\Availability;
use WellCommerce\Bundle\CoreBundle\Test\Entity\AbstractEntityTestCase;

/**
 * Class AvailabilityTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AvailabilityTest extends AbstractEntityTestCase
{
    protected function createEntity()
    {
        return new Availability();
    }
    
    public function providerTestAccessor()
    {
        return [
            ['createdAt', new \DateTime()],
            ['updatedAt', new \DateTime()],
        ];
    }
}
