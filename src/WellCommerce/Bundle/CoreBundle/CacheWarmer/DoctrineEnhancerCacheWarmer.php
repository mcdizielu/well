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

namespace WellCommerce\Bundle\CoreBundle\CacheWarmer;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use WellCommerce\Component\DoctrineEnhancer\TraitGenerator\TraitGenerator;
use WellCommerce\Component\DoctrineEnhancer\TraitGenerator\TraitGeneratorEnhancerCollection;

/**
 * Class DoctrineEnhancerCacheWarmer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class DoctrineEnhancerCacheWarmer implements CacheWarmerInterface
{
    private $collection;
    
    public function __construct(TraitGeneratorEnhancerCollection $collection)
    {
        $this->collection = $collection;
    }
    
    public function warmUp($cacheDir)
    {
        foreach ($this->collection as $traitClass => $enhancers) {
            $generator = new TraitGenerator($traitClass, $enhancers);
            $generator->generate();
        }
    }
    
    /**
     * Checks whether this warmer is optional or not.
     *
     * @return Boolean always true
     */
    public function isOptional()
    {
        return false;
    }
}
