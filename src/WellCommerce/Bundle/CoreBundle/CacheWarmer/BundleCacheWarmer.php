<?php

namespace WellCommerce\Bundle\CoreBundle\CacheWarmer;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\CoreBundle\Locator\BundleLocator;

/**
 * Class BundleCacheWarmer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class BundleCacheWarmer extends CacheWarmer
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function warmUp($cacheDir)
    {
        $locator        = new BundleLocator($this->kernel);
        $bundlesClasses = $locator->getBundleClasses();
        $this->writeCacheFile($cacheDir . '/bundles.php', sprintf('<?php return %s;', var_export($bundlesClasses, true)));
    }
    
    public function isOptional()
    {
        return false;
    }
}
