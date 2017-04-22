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

use Symfony\Component\Config\Loader\LoaderInterface;
use WellCommerce\Bundle\StandardEditionBundle\HttpKernel\AbstractWellcommerceKernel;

/**
 * Class AppKernel
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AppKernel extends AbstractWellcommerceKernel
{
    public function registerBundles()
    {
        $bundles = [
            //add bundles
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            // add bundles in dev/test env.
        }

        return $bundles;
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
