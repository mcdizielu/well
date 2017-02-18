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

namespace WellCommerce\Component\DoctrineEnhancer\ClassMetadata;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class ClassMetadataEnhancerTraverser
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClassMetadataEnhancerTraverser implements ClassMetadataEnhancerTraverserInterface
{
    /**
     * @var ClassMetadataEnhancerCollection
     */
    protected $collection;
    
    public function __construct(ClassMetadataEnhancerCollection $collection)
    {
        $this->collection = $collection;
    }
    
    public function traverse(ClassMetadataInfo $metadata)
    {
        $class = $metadata->getName();
        
        if (true === $this->collection->has($class)) {
            foreach ($this->collection->get($class) as $enhancer) {
                $enhancer->visitClassMetadata($metadata);
            }
        }
    }
}
