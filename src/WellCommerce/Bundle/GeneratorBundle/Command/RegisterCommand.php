<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\GeneratorBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use WellCommerce\Bundle\GeneratorBundle\Model\WellCommerceBundle;
use Wingu\OctopusCore\Reflection\ReflectionFile;

/**
 * Class RegisterCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class RegisterCommand extends AbstractBundleCommand
{
    protected function configure()
    {
        $this->setDescription('Registers a bundle by its prefix');
        $this->setName('wellcommerce:bundle:register');
        
        $this->addOption(
            'prefix',
            null,
            InputOption::VALUE_REQUIRED,
            'Bundle prefix'
        );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $prefix = $input->getOption('prefix');
        
        if ($this->isRegisteredBundle($prefix)) {
            $output->writeln('<error>' . $prefix . ' is already registered in AppKernel.</error>');
            
            return 0;
        }
        
        $bundleClass = $this->findBundleClass($prefix);
        
        if (!$bundleClass) {
            $output->writeln('<error>Bundle class was not found in src/ directory</error>');
            
            return 0;
        }
        
        $rf = new ReflectionFile($bundleClass);
        
        /** @var \ReflectionClass $object */
        foreach ($rf->getObjects() as $object) {
            $bundle = $this->initBundle($object, $prefix);
            $this->manipulator->enableBundle($bundle);
            $output->writeln('<info>' . $bundle->getPrefix() . ' was successfully enabled</info>');
        }
        
        return 0;
    }
    
    private function initBundle(\ReflectionClass $reflectionClass, string $prefix): WellCommerceBundle
    {
        $shortName       = $reflectionClass->getShortName();
        $namespace       = $reflectionClass->getNamespaceName();
        $targetDirectory = dirname($reflectionClass->getFileName());
        
        return new WellCommerceBundle($prefix, $namespace, $shortName, $targetDirectory, 'yml', true);
    }
    
    private function isRegisteredBundle(string $bundleName): bool
    {
        foreach ($this->kernel->getBundles() as $bundle) {
            if ($bundleName === $bundle->getName()) {
                return true;
            }
        }
        
        return false;
    }
    
    private function findBundleClass(string $bundleName)
    {
        $finder = new Finder();
        $finder->in($this->kernel->getRootDir() . '/../src')->name('*' . $bundleName . 'Bundle.php')->depth(3);
        
        foreach ($finder->files() as $file) {
            return $file;
        }
        
        return false;
    }
}
