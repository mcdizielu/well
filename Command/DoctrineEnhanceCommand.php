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

namespace WellCommerce\Bundle\DoctrineBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Environment\EnvironmentHelperInterface;
use WellCommerce\Component\DoctrineEnhancer\TraitGenerator\TraitGenerator;
use WellCommerce\Component\DoctrineEnhancer\TraitGenerator\TraitGeneratorEnhancerCollection;
use WellCommerce\Component\DoctrineEnhancer\TraitGenerator\TraitGeneratorEnhancerTraverserInterface;

/**
 * Class DoctrineEnhanceCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class DoctrineEnhanceCommand extends Command
{
    /**
     * @var EnvironmentHelperInterface
     */
    private $environmentHelper;
    
    /**
     * @var TraitGeneratorEnhancerCollection
     */
    private $collection;
    
    public function __construct(EnvironmentHelperInterface $environmentHelper, TraitGeneratorEnhancerCollection $collection)
    {
        parent::__construct();
        $this->environmentHelper = $environmentHelper;
        $this->collection        = $collection;
    }
    
    protected function configure()
    {
        $this->setDescription('Enhances Doctrine entities');
        $this->setName('wellcommerce:doctrine:enhance');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->collection as $traitClass => $enhancers) {
            $generator = new TraitGenerator($traitClass, $enhancers);
            $code      = $generator->generate();
            
            $output->write('<info>' . $traitClass . '</info>.', true);
            $output->write($code, true);
        }
        
        $this->runProcess([
            'app/console',
            'doctrine:cache:clear-metadata',
        ], $output);
        
        $this->runProcess([
            'app/console',
            'doctrine:schema:update',
            '--force',
        ], $output);
    }
    
    private function runProcess(array $arguments, OutputInterface $output)
    {
        $process = $this->environmentHelper->getProcess($arguments, 360);
        $process->run();
        $output->write($process->getOutput());
    }
}
