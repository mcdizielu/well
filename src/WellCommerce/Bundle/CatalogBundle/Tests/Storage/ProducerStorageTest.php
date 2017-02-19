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

namespace WellCommerce\Bundle\CatalogBundle\Tests\Storage;

use WellCommerce\Bundle\CatalogBundle\Entity\Producer;
use WellCommerce\Bundle\CatalogBundle\Request\Storage\ProducerStorage;
use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;

/**
 * Class ProducerStorageTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProducerStorageTest extends AbstractTestCase
{
    public function testContextReturnsValidData()
    {
        $storage  = new ProducerStorage();
        $producer = new Producer();
        
        $storage->setCurrentProducer($producer);
        $this->assertInstanceOf(Producer::class, $storage->getCurrentProducer());
        $this->assertEquals($producer, $storage->getCurrentProducer());
        $this->assertTrue($storage->hasCurrentProducer());
    }
}
