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

namespace WellCommerce\Bundle\CoreBundle\Helper\Process;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class ProcessHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ProcessHelper implements ProcessHelperInterface
{
    /**
     * @var string
     */
    protected $phpBinaryPath;
    
    /**
     * @var string
     */
    protected $cwd;
    
    public function __construct(string $phpBinaryPath, string $cwd)
    {
        $this->phpBinaryPath = $phpBinaryPath;
        $this->cwd           = $cwd;
    }
    
    public function createProcess(array $arguments, int $timeout = 720): Process
    {
        $command = $this->createProcessBuilder($arguments, $timeout)->getProcess()->getCommandLine();
        $process = new Process($command, $this->cwd);
        $process->setTimeout($timeout);
        
        return $process;
    }
    
    public function createProcessBuilder(array $arguments, $timeout = 720): ProcessBuilder
    {
        $builder = new ProcessBuilder();
        $builder->setPrefix($this->phpBinaryPath);
        $builder->setWorkingDirectory($this->cwd);
        $builder->setArguments($arguments);
        $builder->setTimeout($timeout);
        $builder->inheritEnvironmentVariables(true);
        
        return $builder;
    }
}

