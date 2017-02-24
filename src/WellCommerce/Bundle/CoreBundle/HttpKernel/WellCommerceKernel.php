<?php

namespace WellCommerce\Bundle\CoreBundle\HttpKernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class WellCommerceKernel
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WellCommerceKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new \Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new \Liip\ImagineBundle\LiipImagineBundle(),
            new \Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle(),
            new \Cache\AdapterBundle\CacheAdapterBundle(),
            new \EmanueleMinotto\TwigCacheBundle\TwigCacheBundle(),
        ];
        
        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }
        
        return $bundles;
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
