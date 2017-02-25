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

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AppKernel
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = new ArrayCollection();
        
        \WellCommerce\Bundle\CoreBundle\WellCommerceCoreBundle::registerBundles($bundles);
        \WellCommerce\Bundle\ApiBundle\WellCommerceApiBundle::registerBundles($bundles);
        \WellCommerce\Bundle\AppBundle\WellCommerceAppBundle::registerBundles($bundles);
        \WellCommerce\Bundle\SearchBundle\WellCommerceSearchBundle::registerBundles($bundles);
        \WellCommerce\Bundle\GeneratorBundle\WellCommerceGeneratorBundle::registerBundles($bundles);
        \WellCommerce\Bundle\OrderBundle\WellCommerceOrderBundle::registerBundles($bundles);
        \WellCommerce\Bundle\CatalogBundle\WellCommerceCatalogBundle::registerBundles($bundles);
        \WellCommerce\Bundle\CouponBundle\WellCommerceCouponBundle::registerBundles($bundles);
        \WellCommerce\Bundle\OAuthBundle\WellCommerceOAuthBundle::registerBundles($bundles);
        \WellCommerce\Bundle\CmsBundle\WellCommerceCmsBundle::registerBundles($bundles);
        \WellCommerce\Bundle\ReviewBundle\WellCommerceReviewBundle::registerBundles($bundles);
        \WellCommerce\Bundle\ShowcaseBundle\WellCommerceShowcaseBundle::registerBundles($bundles);
        \WellCommerce\Bundle\WishlistBundle\WellCommerceWishlistBundle::registerBundles($bundles);
        
        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            $bundles->add(new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle());
            $bundles->add(new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle());
        }
        
        return $bundles->toArray();
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
