<?php

namespace WellCommerce\Bundle\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\CoreBundle\Console\ConsoleActionExecutor;
use WellCommerce\Bundle\CoreBundle\Generator\WellCommerceBundleGenerator;
use WellCommerce\Bundle\GeneratorBundle\Manipulator\WellCommerceManipulator;

/**
 * Class AbstractBundleCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractBundleCommand extends ContainerAwareCommand
{
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    /**
     * @var Filesystem
     */
    protected $filesystem;
    
    /**
     * @var ConsoleActionExecutor
     */
    protected $executor;
    
    /**
     * @var WellCommerceBundleGenerator
     */
    protected $generator;
    
    /**
     * @var WellCommerceManipulator
     */
    protected $manipulator;
    
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->kernel      = $this->getContainer()->get('kernel');
        $this->filesystem  = new Filesystem();
        $this->executor    = $this->getContainer()->get('distribution.console.action_executor');
        $this->generator   = $this->getContainer()->get('distribution.bundle.generator');
        $this->manipulator = $this->getContainer()->get('distribution.bundle.manipulator');
    }
}
