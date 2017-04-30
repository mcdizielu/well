<?php

namespace WellCommerce\Bundle\GeneratorBundle\Manipulator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigurationManipulator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ConfigurationManipulator
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    
    /**
     * @var string
     */
    private $file;
    
    /**
     * @var Filesystem
     */
    private $filesystem;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel     = $kernel;
        $this->filesystem = new Filesystem();
        $this->file       = $this->kernel->getRootDir() . '/config/wellcommerce.yml';
    }
    
    public function addBundle(BundleInterface $bundle)
    {
        $code            = $this->getImportCode($bundle);
        $currentContents = file_get_contents($this->file);
        
        if (false === strpos($currentContents, $code)) {
            $lastImportedPath   = $this->findLastImportedPath($currentContents);
            $importsPosition    = strpos($currentContents, 'imports:');
            $lastImportPosition = strpos($currentContents, $lastImportedPath, $importsPosition);
            $targetPosition     = strpos($currentContents, "\n", $lastImportPosition);
            $newContents        = substr($currentContents, 0, $targetPosition) . "\n" . $code . substr($currentContents, $targetPosition);
            
            $this->filesystem->dumpFile($this->file, $newContents);
        }
    }
    
    public function removeBundle(BundleInterface $bundle)
    {
    
    }
    
    private function getImportCode(BundleInterface $bundle)
    {
        return sprintf(<<<EOF
    - { resource: "@%s/Resources/config/config.yml" }
EOF
            ,
            $bundle->getName()
        );
    }
    
    private function findLastImportedPath(string $content)
    {
        $data = Yaml::parse($content);
        if (!isset($data['imports'])) {
            return false;
        }
        
        $lastImport = end($data['imports']);
        if (!isset($lastImport['resource'])) {
            return false;
        }
        
        return $lastImport['resource'];
    }
}
