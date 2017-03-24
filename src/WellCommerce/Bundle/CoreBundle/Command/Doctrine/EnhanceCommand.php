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

namespace WellCommerce\Bundle\CoreBundle\Command\Doctrine;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WellCommerce\Bundle\CoreBundle\Helper\Process\ProcessHelperInterface;
use WellCommerce\Component\DoctrineEnhancer\TraitGenerator\TraitGenerator;
use WellCommerce\Component\DoctrineEnhancer\TraitGenerator\TraitGeneratorEnhancerCollection;

/**
 * Class EnhanceCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class EnhanceCommand extends Command
{
    /**
     * @var ProcessHelperInterface
     */
    private $processHelper;
    
    /**
     * @var TraitGeneratorEnhancerCollection
     */
    private $collection;
    
    public function __construct(ProcessHelperInterface $processHelper, TraitGeneratorEnhancerCollection $collection)
    {
        parent::__construct();
        $this->processHelper = $processHelper;
        $this->collection    = $collection;
    }
    
    protected function configure()
    {
        $this->setDescription('Enhances Doctrine entities');
        $this->setName('wellcommerce:doctrine:enhance');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generatedTraits = [];
        
        $io = new SymfonyStyle($input, $output);
        $io->newLine();
        
        foreach ($this->collection as $traitClass => $enhancers) {
            $generator = new TraitGenerator($traitClass, $enhancers);
            $generator->generate();
            
            $generatedTraits[] = [
                sprintf('<fg=green;options=bold>%s</>', '\\' === DIRECTORY_SEPARATOR ? 'OK' : "\xE2\x9C\x94"),
                $traitClass,
            ];
        }
        
        $io->table(['', 'Generated trait'], $generatedTraits);
        
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
        $process = $this->processHelper->createProcess($arguments);
        $process->run();
        $output->write($process->getOutput());
    }
}
