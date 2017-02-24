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

namespace WellCommerce\Bundle\OrderBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use WellCommerce\Bundle\AppBundle\Entity\Currency;
use WellCommerce\Bundle\AppBundle\Entity\Tax;
use WellCommerce\Bundle\CoreBundle\Test\Entity\AbstractEntityTestCase;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;

/**
 * Class ShippingMethodTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShippingMethodTest extends AbstractEntityTestCase
{
    protected function createEntity()
    {
        return new ShippingMethod();
    }
    
    public function providerTestAccessor()
    {
        return [
            ['tax', new Tax()],
            ['currency', new Currency()],
            ['shops', new ArrayCollection()],
            ['costs', new ArrayCollection()],
            ['calculator', 'fixed_price'],
            ['optionsProvider', 'fedex'],
            ['countries', []],
            ['countries', [1, 2, 3]],
            ['createdAt', new \DateTime()],
            ['updatedAt', new \DateTime()],
        ];
    }
}
