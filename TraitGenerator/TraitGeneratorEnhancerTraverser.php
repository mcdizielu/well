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

namespace WellCommerce\Component\DoctrineEnhancer\TraitGenerator;

use Wingu\OctopusCore\CodeGenerator\PHP\OOP\TraitGenerator;

/**
 * Class TraitGeneratorEnhancerTraverser
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class TraitGeneratorEnhancerTraverser implements TraitGeneratorEnhancerTraverserInterface
{
    /**
     * @var TraitGeneratorEnhancerCollection
     */
    protected $collection;
    
    public function __construct(TraitGeneratorEnhancerCollection $collection)
    {
        $this->collection = $collection;
    }
    
    public function traverse(TraitGenerator $generator)
    {
        $class = ltrim($generator->getFullyQualifiedName(), '\\');
        if (true === $this->collection->has($class)) {
            foreach ($this->collection->get($class) as $enhancer) {
                $enhancer->visitTraitGenerator($generator);
            }
        }
    }
}
