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

namespace WellCommerce\Bundle\CoreBundle\Console\Executor;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\CoreBundle\Console\Action\ConsoleActionInterface;

/**
 * Class ConsoleActionExecutor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ConsoleActionExecutor implements ConsoleActionExecutorInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    /**
     * @var Application
     */
    protected $application;
    
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function execute(array $actions = [], ConsoleOutputInterface $output = null)
    {
        $this->application = new Application($this->kernel);
        $this->initOutput($output);
        
        foreach ($actions as $action) {
            $this->runAction($action);
        }
    }
    
    protected function initOutput(ConsoleOutputInterface $output = null)
    {
        if (null === $output) {
            $this->output = ('cli' === PHP_SAPI) ? new ConsoleOutput() : new NullOutput();
        } else {
            $this->output = $output;
        }
    }
    
    protected function runAction(ConsoleActionInterface $action)
    {
        $commands = $action->getCommandsToExecute();
        foreach ($commands as $command => $options) {
            $arguments = array_merge(['command' => $command], $options);
            $this->runCommand($arguments);
        }
    }
    
    protected function runCommand(array $arguments)
    {
        $input = new ArrayInput($arguments);
        $input->setInteractive(false);
        
        return $this->application->doRun($input, $this->output);
    }
}
