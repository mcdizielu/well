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

namespace WellCommerce\Bundle\CoreBundle\Helper\Environment;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class EnvironmentHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class EnvironmentHelper implements EnvironmentHelperInterface
{
    const DEFAULT_PROCESS_TIMEOUT = 360;
    
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function getProcess(array $arguments, $timeout = self::DEFAULT_PROCESS_TIMEOUT)
    {
        $command = $this->getProcessBuilder($arguments, $timeout)->getProcess()->getCommandLine();
        $process = new Process($command, $this->getCwd());
        $process->setTimeout($timeout);
        
        return $process;
    }
    
    public function getPhpBinary()
    {
        if ($this->kernel->getContainer()->hasParameter('php_binary_path')) {
            return $this->kernel->getContainer()->hasParameter('php_binary_path');
        }
        
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find(true)) {
            $process = new Process('whereis php');
            $process->run();
            $output = $process->getOutput();
            $lines  = explode(PHP_EOL, $output);
            if (count($lines) > 0) {
                $phpPath = current($lines);
            }
        }
        
        return $phpPath;
    }
    
    public function getCwd()
    {
        return $this->kernel->getRootDir() . '/../';
    }
    
    public function getFreePort()
    {
        return 8080;
    }
    
    public function getProcessBuilder(array $arguments, $timeout = self::DEFAULT_PROCESS_TIMEOUT)
    {
        $builder = new ProcessBuilder();
        $builder->setPrefix($this->getPhpBinary());
        $builder->setWorkingDirectory($this->getCwd());
        $builder->setArguments($arguments);
        $builder->setTimeout($timeout);
        $builder->inheritEnvironmentVariables(true);
        $builder->setEnv('COMPOSER_HOME', $this->kernel->getRootDir() . '/.composer');
        
        return $builder;
    }
    
    public function getComposerPhar()
    {
        return 'composer.phar';
    }
}

