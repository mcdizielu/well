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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class GenerateCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class GenerateCommand extends AbstractBundleCommand
{
    protected function configure()
    {
        $this->setDescription('Generates the bundle skeleton');
        $this->setName('wellcommerce:bundle:generate');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $vendor = $this->askVendor($input, $output);
        $prefix = $this->askPrefix($input, $output);
        $bundle = $this->generator->generateBundle($vendor, $prefix);
        
        $output->writeln('<info>New bundle was generated in ' . $bundle->getTargetDirectory() . '</info>');
        
        $helper   = $this->getHelper('question');
        $question = new ConfirmationQuestion('Do you want to auto-register bundle (y/n)?', true);
        
        if (!$helper->ask($input, $output, $question)) {
            return;
        }
        
        $this->manipulator->enableBundle($bundle);
        
        $output->writeln('<info>' . $bundle->getName() . ' is now enabled.</info>');
    }
    
    private function askVendor(InputInterface $input, OutputInterface $output): string
    {
        $helper   = $this->getHelper('question');
        $question = new Question('Please enter the vendor name (default: WellCommerce): ', 'WellCommerce');
        $question->setNormalizer(function ($value) {
            return $value ? $this->normalize($value) : '';
        });
        
        $bundle = $helper->ask($input, $output, $question);
        
        return $bundle;
    }
    
    private function askPrefix(InputInterface $input, OutputInterface $output): string
    {
        $helper   = $this->getHelper('question');
        $question = new Question('Please enter the prefix of the bundle (default: Demo): ', 'Demo');
        $question->setNormalizer(function ($value) {
            return $value ? $this->normalize($value) : '';
        });
        $bundle = $helper->ask($input, $output, $question);
        
        return $bundle;
    }
    
    private function normalize(string $value): string
    {
        $replacements = [' ', '-', '_'];
        
        return str_replace($replacements, '', ucfirst(trim($value)));
    }
}
