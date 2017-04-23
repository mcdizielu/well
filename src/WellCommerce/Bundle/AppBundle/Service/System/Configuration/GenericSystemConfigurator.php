<?php

namespace WellCommerce\Bundle\AppBundle\Service\System\Configuration;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Process\Process;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class ConfigurationProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class GenericSystemConfigurator extends AbstractSystemConfigurator
{
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form)
    {
        $generalData = $form->addChild($builder->getElement('nested_fieldset', [
            'name'  => 'general',
            'label' => 'system_configuration.fieldset.general',
        ]));
    
        $generalData->addChild($builder->getElement('tip', [
            'tip' => 'system_configuration.tip.wkhtmlbinary',
        ]));
        
        $generalData->addChild($builder->getElement('select', [
            'name'    => 'wkhtmlbinary',
            'label'   => 'system_configuration.label.wkhtmlbinary',
            'options' => $this->getBinaries(),
            'default' => $this->getParamValue('wkhtmlbinary'),
        ]))->setValue($this->getParamValue('wkhtmlbinary'));
        
        $algoliaSettings = $form->addChild($builder->getElement('nested_fieldset', [
            'name'  => 'algolia',
            'label' => 'system_configuration.fieldset.algolia',
        ]));
        
        $algoliaSettings->addChild($builder->getElement('tip', [
            'tip' => 'system_configuration.tip.algolia',
        ]));
        
        $algoliaSettings->addChild($builder->getElement('text_field', [
            'name'  => 'algolia_id',
            'label' => 'system_configuration.label.algolia_id',
        ]))->setValue($this->getParamValue('algolia_id'));
        
        $algoliaSettings->addChild($builder->getElement('text_field', [
            'name'  => 'algolia_key',
            'label' => 'system_configuration.label.algolia_key',
        ]))->setValue($this->getParamValue('algolia_key'));
    }
    
    public function saveParameters(array $data)
    {
        $this->setParamValue('wkhtmlbinary', $data['general']['wkhtmlbinary'] ?? $this->getDefaults()['wkhtmlbinary']);
        $this->setParamValue('algolia_id', $data['algolia']['wkhtmlbinary'] ?? $this->getDefaults()['algolia_id']);
        $this->setParamValue('algolia_key', $data['algolia']['wkhtmlbinary'] ?? $this->getDefaults()['algolia_key']);
    }
    
    public function getDefaults(): array
    {
        return [
            'wkhtmlbinary' => $this->getDefaultWkhtmlBinary(),
            'algolia_id'   => 'C2VGT4PGWH',
            'algolia_key'  => 'b48fb9f6a4eee57f10e3079af095533b',
        ];
    }
    
    private function getDefaultWkhtmlBinary(): string
    {
        $default = '';
        $cwd     = $this->kernel->getRootDir() . '/../bin';
        foreach ($this->getBinaries() as $binary) {
            $process = new Process($cwd . '/' . $binary . ' -V');
            $process->run();
            if (0 === $process->getExitCode()) {
                $default = $binary;
                break;
            }
        }
        
        return $default;
    }
    
    private function getBinaries(): array
    {
        $dir      = $this->kernel->getRootDir() . '/../bin';
        $finder   = new Finder();
        $binaries = [];
        
        /** @var SplFileInfo $file */
        foreach ($finder->files()->in($dir)->name('wkhtmltopdf-*') as $file) {
            $binaries[$file->getFilename()] = $file->getFilename();
        }
        
        return $binaries;
    }
}
