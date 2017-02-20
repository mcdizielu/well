<?php

namespace WellCommerce\Bundle\GeneratorBundle\Manipulator;

use Sensio\Bundle\GeneratorBundle\Manipulator\ConfigurationManipulator;
use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;
use Sensio\Bundle\GeneratorBundle\Manipulator\RoutingManipulator;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\GeneratorBundle\Model\WellCommerceBundle;

/**
 * Class WellCommerceManipulator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class WellCommerceManipulator
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    
    /**
     * @var string
     */
    private $rootDir;
    
    /**
     * WellCommerceManipulator constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel  = $kernel;
        $this->rootDir = $this->kernel->getRootDir() . '/config/';
    }
    
    public function enableBundle(WellCommerceBundle $bundle)
    {
        $this->enableKernel($bundle);
        $this->enableRouting($bundle);
        $this->enableConfiguration($bundle);
    }
    
    private function enableKernel(WellCommerceBundle $bundle)
    {
        $manipulator = new KernelManipulator($this->kernel);
        $manipulator->addBundle($bundle->getBundleClassName());
    }
    
    private function enableRouting(WellCommerceBundle $bundle)
    {
        $file        = $this->rootDir . '/routing.yml';
        $manipulator = new RoutingManipulator($file);
        $manipulator->addResource($bundle->getName(), 'yml');
    }
    
    private function enableConfiguration(WellCommerceBundle $bundle)
    {
        $file        = $this->rootDir . '/wellcommerce.yml';
        $manipulator = new ConfigurationManipulator($file);
        $manipulator->addResource($bundle);
    }
}
