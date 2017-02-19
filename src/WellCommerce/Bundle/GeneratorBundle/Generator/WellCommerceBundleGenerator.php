<?php

namespace WellCommerce\Bundle\GeneratorBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\GeneratorBundle\Model\WellCommerceBundle;

/**
 * Class WellCommerceBundleGenerator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class WellCommerceBundleGenerator
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    
    /**
     * @var \Twig_Environment
     */
    private $twig;
    
    /**
     * @var Filesystem
     */
    private $filesystem;
    
    /**
     * WellCommerceBundleGenerator constructor.
     *
     * @param KernelInterface   $kernel
     * @param \Twig_Environment $twig
     */
    public function __construct(KernelInterface $kernel, \Twig_Environment $twig)
    {
        $this->kernel     = $kernel;
        $this->twig       = $twig;
        $this->filesystem = new Filesystem();
    }
    
    public function generateBundle(string $vendor, string $prefix): WellCommerceBundle
    {
        $bundleClass = $this->generateBundleClass($vendor, $prefix);
        $bundle      = $this->initBundle($prefix, $bundleClass);
        
        $this->generateClasses($bundle);
        $this->generateConfiguration($bundle);
        $this->generateRouting($bundle);
        $this->generateDoctrineConfiguration($bundle);
        $this->generateTemplates($bundle);
        
        return $bundle;
    }
    
    private function initBundle(string $prefix, string $bundleClass): WellCommerceBundle
    {
        $rc              = new \ReflectionClass($bundleClass);
        $shortName       = $rc->getShortName();
        $namespace       = $rc->getNamespaceName();
        $targetDirectory = $this->kernel->getRootDir() . '/../src';
        
        return new WellCommerceBundle($prefix, $namespace, $shortName, $targetDirectory, 'yml', true);
    }
    
    private function generateBundleClass(string $vendor, string $prefix): string
    {
        $kernelDir       = $this->kernel->getRootDir() . '/../src';
        $baseDir         = sprintf('%s/%s/Bundle/%sBundle', $kernelDir, $vendor, $prefix);
        $targetFilename  = sprintf('%s/%s%sBundle.php', $baseDir, $vendor, $prefix);
        $bundleClassName = $vendor . '\\Bundle\\' . $prefix . 'Bundle\\' . $vendor . $prefix . 'Bundle';
        
        $content = $this->twig->render('WellCommerceCoreBundle:skeleton/bundle:Bundle.php.twig', [
            'prefix' => $prefix,
            'vendor' => $vendor,
        ]);
        
        $this->filesystem->dumpFile($targetFilename, $content);
        
        return $bundleClassName;
    }
    
    private function generateClasses(WellCommerceBundle $bundle)
    {
        $classTemplates = [
            'Admin/Controller.php.twig'           => sprintf('Controller/Admin/%sController.php', $bundle->getPrefix()),
            'Admin/DataSet.php.twig'              => sprintf('DataSet/Admin/%sDataSet.php', $bundle->getPrefix()),
            'Admin/FormBuilder.php.twig'          => sprintf('Form/Admin/%sFormBuilder.php', $bundle->getPrefix()),
            'Admin/DataGrid.php.twig'             => sprintf('DataGrid/%sDataGrid.php', $bundle->getPrefix()),
            'Admin/Manager.php.twig'              => sprintf('Manager/%sManager.php', $bundle->getPrefix()),
            'Front/Controller.php.twig'           => sprintf('Controller/Front/%sController.php', $bundle->getPrefix()),
            'Front/DataSet.php.twig'              => sprintf('DataSet/Front/%sDataSet.php', $bundle->getPrefix()),
            'Doctrine/Entity.php.twig'            => sprintf('Entity/%s.php', $bundle->getPrefix()),
            'Doctrine/EntityTranslation.php.twig' => sprintf('Entity/%sTranslation.php', $bundle->getPrefix()),
        
        ];
        
        foreach ($classTemplates as $template => $target) {
            $content = $this->twig->render('WellCommerceCoreBundle:skeleton/bundle:' . $template, [
                'bundle' => $bundle,
            ]);
            
            $this->filesystem->dumpFile($bundle->getTargetDirectory() . '/' . $target, $content);
        }
    }
    
    private function generateConfiguration(WellCommerceBundle $bundle)
    {
        $content        = $this->twig->render('WellCommerceCoreBundle:skeleton/bundle:config/config.yml.twig');
        $targetFilename = $bundle->getTargetDirectory() . '/Resources/config/config.yml';
        
        $this->filesystem->dumpFile($targetFilename, $content);
    }
    
    private function generateRouting(WellCommerceBundle $bundle)
    {
        $content = $this->twig->render('WellCommerceCoreBundle:skeleton/bundle:config/routing.yml.twig', [
            'bundle' => $bundle,
        ]);
        
        $targetFilename = $bundle->getTargetDirectory() . '/Resources/config/routing.yml';
        
        $this->filesystem->dumpFile($targetFilename, $content);
    }
    
    private function generateDoctrineConfiguration(WellCommerceBundle $bundle)
    {
        $ormMappingFilename = sprintf('%s.orm.yml', $bundle->getPrefix());
        $ormTemplate        = 'WellCommerceCoreBundle:skeleton/bundle:Doctrine/Entity.orm.yml.twig';
        $ormTargetFilename  = $bundle->getTargetDirectory() . '/Resources/config/doctrine/' . $ormMappingFilename;
        $ormMappingContent  = $this->twig->render($ormTemplate, [
            'bundle' => $bundle,
        ]);
        
        $ormTranslationMappingFilename = sprintf('%sTranslation.orm.yml', $bundle->getPrefix());
        $ormTranslationTemplate        = 'WellCommerceCoreBundle:skeleton/bundle:Doctrine/EntityTranslation.orm.yml.twig';
        $ormTranslationTargetFilename  = $bundle->getTargetDirectory() . '/Resources/config/doctrine/' . $ormTranslationMappingFilename;
        $ormTranslationMappingContent  = $this->twig->render($ormTranslationTemplate, [
            'bundle' => $bundle,
        ]);
        
        $this->filesystem->dumpFile($ormTargetFilename, $ormMappingContent);
        $this->filesystem->dumpFile($ormTranslationTargetFilename, $ormTranslationMappingContent);
    }
    
    private function generateTemplates(WellCommerceBundle $bundle)
    {
        $files = [
            'Admin/add.html.twig',
            'Admin/edit.html.twig',
            'Admin/index.html.twig',
            'Front/index.html.twig',
            'Front/view.html.twig',
        ];
        
        foreach ($files as $file) {
            $content = $this->twig->render('WellCommerceCoreBundle:skeleton/bundle:views/' . $file, [
                'bundle' => $bundle,
            ]);
            
            $this->filesystem->dumpFile($bundle->getTargetDirectory() . '/Resources/views/' . $file, $content);
        }
    }
}
