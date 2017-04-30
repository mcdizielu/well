<?php

namespace WellCommerce\Bundle\GeneratorBundle\Manipulator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class KernelManipulator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class KernelManipulator
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    
    /**
     * @var Filesystem
     */
    private $filesystem;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel     = $kernel;
        $this->filesystem = new Filesystem();
    }
    
    public function addBundle(BundleInterface $bundle)
    {
        if (true === $this->isAlreadyRegistered($bundle)) {
            $reflectionClass  = new \ReflectionClass($this->kernel);
            $src              = file($reflectionClass->getFileName());
            $reflectionObject = new \ReflectionObject($this->kernel);
            $method           = $reflectionObject->getMethod('registerBundles');
            $lines            = array_slice($src, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1);
        }
    }
    
    public function removeBundle(BundleInterface $bundle)
    {
    
    }
    
    private function isAlreadyRegistered(BundleInterface $bundle): bool
    {
        foreach ($this->kernel->getBundles() as $kernelBundle) {
            if ($kernelBundle->getName() === $bundle->getName()) {
                return true;
            }
        }
        
        return false;
    }
}
