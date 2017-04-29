<?php

namespace WellCommerce\Bundle\AppBundle\Service\System\Configuration;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Process\Process;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class WkhtmlSystemConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WkhtmlSystemConfigurator extends AbstractSystemConfigurator
{
    public function getAlias(): string
    {
        return 'wkhtml';
    }

    public function addFormFields(FormBuilderInterface $builder, FormInterface $form)
    {
        $generalData = $form->addChild($builder->getElement('nested_fieldset', [
            'name'  => 'wkhtml',
            'label' => 'wkhtml.fieldset.settings',
        ]));

        $generalData->addChild($builder->getElement('tip', [
            'tip' => 'wkhtml.tip.binary',
        ]));
        
        $generalData->addChild($builder->getElement('select', [
            'name'    => 'binary',
            'label'   => 'wkhtml.label.binary',
            'options' => $this->getBinaries(),
        ]))->setValue($this->getParameter('binary'));
    }
    
    public function getDefaults(): array
    {
        return [
            'binary' => $this->getDefaultWkhtmlBinary(),
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
