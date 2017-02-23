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

namespace WellCommerce\Bundle\CatalogBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use WellCommerce\Bundle\AppBundle\Entity\Dimension;
use WellCommerce\Bundle\AppBundle\Entity\DiscountablePrice;
use WellCommerce\Bundle\AppBundle\Entity\Media;
use WellCommerce\Bundle\AppBundle\Entity\Price;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeGroup;
use WellCommerce\Bundle\CatalogBundle\Entity\Availability;
use WellCommerce\Bundle\CatalogBundle\Entity\Producer;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\Unit;
use WellCommerce\Bundle\CoreBundle\Test\Entity\AbstractEntityTestCase;

/**
 * Class ProductTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductTest extends AbstractEntityTestCase
{
    protected function createEntity()
    {
        return new Product();
    }
    
    public function providerTestAccessor()
    {
        $faker = $this->getFakerGenerator();
        
        return [
            ['photo', new Media()],
            ['producer', new Producer()],
            ['unit', new Unit()],
            ['availability', new Availability()],
            ['sku', $faker->randomDigit],
            ['stock', $faker->randomFloat(0)],
            ['weight', $faker->randomFloat(2)],
            ['packageSize', $faker->randomFloat(2)],
            ['attributeGroup', new AttributeGroup()],
            ['categories', new ArrayCollection()],
            ['productPhotos', new ArrayCollection()],
            ['distinctions', new ArrayCollection()],
            ['variants', new ArrayCollection()],
            ['shops', new ArrayCollection()],
            ['sellPrice', new DiscountablePrice()],
            ['buyPrice', new Price()],
            ['dimension', new Dimension()],
        ];
    }
}
