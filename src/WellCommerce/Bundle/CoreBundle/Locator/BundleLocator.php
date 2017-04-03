<?php

namespace WellCommerce\Bundle\CoreBundle\Locator;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\NullIO;
use Composer\Package\PackageInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class BundleLoader
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class BundleLocator
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function getBundleClasses(): array
    {
        $composerInstalledBundleClasses = $this->getComposerInstalledBundleClasses();
        $localBundles                   = [];
        
        return array_merge($composerInstalledBundleClasses, $localBundles);
    }
    
    private function getComposerInstalledBundleClasses(): array
    {
        $bundles         = [];
        $composer        = $this->getComposer();
        $localRepository = $composer->getRepositoryManager()->getLocalRepository();
        
        /** @var PackageInterface $package */
        foreach ($localRepository->getPackages() as $package) {
            $bundleClasses = $package->getExtra()['register-bundles'] ?? [];
            foreach ($bundleClasses as $bundleClass) {
                $bundles[$bundleClass] = $bundleClass;
            }
        }
        
        return $bundles;
    }
    
    private function getComposer(): Composer
    {
        putenv('COMPOSER_HOME=' . $this->kernel->getRootDir() . '/.composer');
        
        $composerJson = $this->kernel->getRootDir() . '/../composer.json';
        
        return Factory::create(new NullIO(), $composerJson);
    }
}
