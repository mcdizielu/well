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
use WellCommerce\Bundle\CoreBundle\Loader\BundleLoader;
use WellCommerce\Bundle\StandardEditionBundle\WellCommerceStandardEditionBundle;

/**
 * Class AppKernel
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $loader  = new BundleLoader($this);
        $bundles = new ArrayCollection();
        
        WellCommerceStandardEditionBundle::registerBundles($bundles, $this->getEnvironment());
        $loader->registerBundles($bundles, $this->getEnvironment());
        
        return $bundles->toArray();
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
