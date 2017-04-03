<?php

namespace WellCommerce\Bundle\CoreBundle\Loader;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\CoreBundle\Locator\BundleLocator;

/**
 * Class BundleLoader
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class BundleLoader
{
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function registerBundles(Collection $collection, string $environment)
    {
        if (is_file($cache = $this->kernel->getCacheDir() . '/bundles.php')) {
            $bundleClasses = require $cache;
        } else {
            $bundleClasses = $this->getLocator()->getBundleClasses();
        }
        
        foreach ($bundleClasses as $bundleClass) {
            $collection->add(new $bundleClass);
        }
    }
    
    private function getLocator(): BundleLocator
    {
        return new BundleLocator($this->kernel);
    }
}
