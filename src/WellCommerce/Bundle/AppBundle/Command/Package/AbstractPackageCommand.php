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

namespace WellCommerce\Bundle\AppBundle\Command\Package;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WellCommerce\Bundle\AppBundle\Entity\Package;
use WellCommerce\Bundle\CoreBundle\Helper\Package\PackageHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Process\ProcessHelperInterface;

/**
 * Class AbstractCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractPackageCommand extends ContainerAwareCommand
{
    protected $composerOperation = 'update';
    
    protected function configure()
    {
        $this->addOption('package', null, InputOption::VALUE_REQUIRED, 'Name of the package');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        putenv('COMPOSER_HOME=' . $this->getContainer()->get('kernel')->getRootDir() . '/.composer');
        
        $package   = $this->getPackage($input->getOption('package'));
        $arguments = $this->getCommandArguments($package);
        $process   = $this->getProcessHelper()->createProcess($arguments);
        $process->start();
        
        foreach ($process as $type => $data) {
            echo $data . "\n";
        }
    }
    
    protected function getCommandArguments(Package $package): array
    {
        return [
            'composer.phar',
            $this->composerOperation,
            $package->getFullName(),
        ];
    }
    
    protected function getPackage(string $name): Package
    {
        return $this->getContainer()->get('package.repository')->findOneBy(['name' => $name]);
    }
    
    protected function getProcessHelper(): ProcessHelperInterface
    {
        return $this->getContainer()->get('process.helper');
    }
    
    protected function getPackageHelper(): PackageHelperInterface
    {
        return $this->getContainer()->get('package.helper');
    }
}
