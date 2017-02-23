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

namespace WellCommerce\Bundle\CoreBundle\Test\Entity;

use Symfony\Component\PropertyAccess\PropertyAccess;
use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;

/**
 * Class AbstractEntityTestCase
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractEntityTestCase extends AbstractTestCase
{
    /**
     * @dataProvider providerTestAccessor
     */
    public function testAccessor($property, $setValue, $getValue = null)
    {
        if (null === $getValue) {
            $getValue = $setValue;
        }
        
        $entity           = $this->createEntity();
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        
        $this->assertTrue($propertyAccessor->isWritable($entity, $property));
        $propertyAccessor->setValue($entity, $property, $setValue);
        $this->assertTrue($propertyAccessor->isReadable($entity, $property));
        $this->assertEquals($getValue, $propertyAccessor->getValue($entity, $property));
    }
    
    abstract protected function createEntity();
}
