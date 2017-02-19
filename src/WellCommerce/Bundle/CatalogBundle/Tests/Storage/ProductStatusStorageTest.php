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

namespace WellCommerce\Bundle\CatalogBundle\Tests\Context\Front;

use WellCommerce\Bundle\CatalogBundle\Entity\ProductStatus;
use WellCommerce\Bundle\CatalogBundle\Request\Storage\ProductStatusStorage;
use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;

/**
 * Class ProductStatusStorageTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductStatusStorageTest extends AbstractTestCase
{
    public function testContextReturnsValidData()
    {
        $storage = new ProductStatusStorage();
        $status  = new ProductStatus();
        $storage->setCurrentProductStatus($status);
        
        $this->assertInstanceOf(ProductStatus::class, $storage->getCurrentProductStatus());
        $this->assertEquals($status, $storage->getCurrentProductStatus());
        $this->assertTrue($storage->hasCurrentProductStatus());
    }
}
