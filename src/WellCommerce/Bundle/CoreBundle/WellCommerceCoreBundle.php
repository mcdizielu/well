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

namespace WellCommerce\Bundle\CoreBundle;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\Compiler;
use WellCommerce\Bundle\CoreBundle\HttpKernel\AbstractWellCommerceBundle;

/**
 * Class WellCommerceCoreBundle
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class WellCommerceCoreBundle extends AbstractWellCommerceBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new Compiler\FormDataTransformerPass());
        $container->addCompilerPass(new Compiler\FormResolverPass());
        $container->addCompilerPass(new Compiler\DataSetContextPass());
        $container->addCompilerPass(new Compiler\DataSetTransformerPass());
        $container->addCompilerPass(new Compiler\RegisterTraitGeneratorEnhancerPass());
        $container->addCompilerPass(new Compiler\RegisterClassMetadataEnhancerPass());
    }
    
    public static function registerBundles(Collection $bundles, string $environment)
    {
        $bundles->add(new \Symfony\Bundle\FrameworkBundle\FrameworkBundle());
        $bundles->add(new \Symfony\Bundle\SecurityBundle\SecurityBundle());
        $bundles->add(new \Symfony\Bundle\TwigBundle\TwigBundle());
        $bundles->add(new \Symfony\Bundle\MonologBundle\MonologBundle());
        $bundles->add(new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle());
        $bundles->add(new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle());
        $bundles->add(new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle());
        $bundles->add(new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle());
        $bundles->add(new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle());
        $bundles->add(new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle());
        $bundles->add(new \FOS\JsRoutingBundle\FOSJsRoutingBundle());
        $bundles->add(new \Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle());
        $bundles->add(new \Liip\ImagineBundle\LiipImagineBundle());
        $bundles->add(new \Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle());
        $bundles->add(new \Cache\AdapterBundle\CacheAdapterBundle());
        $bundles->add(new \EmanueleMinotto\TwigCacheBundle\TwigCacheBundle());
        $bundles->add(new \Knp\Bundle\SnappyBundle\KnpSnappyBundle());
        
        $bundles->add(new self());
        
        if (in_array($environment, ['dev', 'test'])) {
            $bundles->add(new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle());
            $bundles->add(new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle());
        }
    }
}
