<?php

namespace WellCommerce\Bundle\GeneratorBundle\Manipulator;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class RoutingManipulator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class RoutingManipulator
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    
    /**
     * @var string
     */
    private $file;
    
    /**
     * @var Filesystem
     */
    private $filesystem;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel     = $kernel;
        $this->filesystem = new Filesystem();
        $this->file       = $this->kernel->getRootDir() . '/config/routing.yml';
    }
    
    public function addBundle(BundleInterface $bundle)
    {
        $snakeCasedBundleName = Container::underscore(substr($bundle->getName(), 0, -6));
        $contents             = Yaml::parse(file_get_contents($this->file));
        
        if (!isset($contents[$snakeCasedBundleName])) {
            $contents[$snakeCasedBundleName]['resource'] = sprintf('@%s/Resources/config/routing.yml', $bundle->getName());
        }
        
        $this->filesystem->dumpFile($this->file, Yaml::dump($contents));
    }
    
    public function removeBundle(BundleInterface $bundle)
    {
    
    }
}
