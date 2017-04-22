<?php

namespace WellCommerce\Bundle\StandardEditionBundle\HttpKernel;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Kernel;
use WellCommerce\Bundle\CoreBundle\Loader\BundleLoader;
use WellCommerce\Bundle\StandardEditionBundle\WellCommerceStandardEditionBundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

abstract class AbstractWellcommerceKernel extends Kernel
{
    protected function initializeBundles()
    {
        $loader  = new BundleLoader($this);
        $bundles = new ArrayCollection();


        WellCommerceStandardEditionBundle::registerBundles($bundles, $this->getEnvironment());
        $loader->registerBundles($bundles, $this->getEnvironment());

        $bundles = array_merge($this->registerBundles(), $bundles->toArray());

        // init bundles
        $this->bundles = array();
        $topMostBundles = array();
        $directChildren = array();

        foreach ($bundles as $bundle) {

            /** @var  BundleInterface $bundle */
            $name = $bundle->getName();
            if (isset($this->bundles[$name])) {
                throw new \LogicException(sprintf('Trying to register two bundles with the same name "%s"', $name));
            }
            $this->bundles[$name] = $bundle;

            if ($parentName = $bundle->getParent()) {
                if (isset($directChildren[$parentName])) {
                    throw new \LogicException(sprintf('Bundle "%s" is directly extended by two bundles "%s" and "%s".', $parentName, $name, $directChildren[$parentName]));
                }
                if ($parentName == $name) {
                    throw new \LogicException(sprintf('Bundle "%s" can not extend itself.', $name));
                }
                $directChildren[$parentName] = $name;
            } else {
                $topMostBundles[$name] = $bundle;
            }
        }

        // look for orphans
        if (!empty($directChildren) && count($diff = array_diff_key($directChildren, $this->bundles))) {
            $diff = array_keys($diff);

            throw new \LogicException(sprintf('Bundle "%s" extends bundle "%s", which is not registered.', $directChildren[$diff[0]], $diff[0]));
        }

        // inheritance
        $this->bundleMap = array();
        foreach ($topMostBundles as $name => $bundle) {
            $bundleMap = array($bundle);
            $hierarchy = array($name);

            while (isset($directChildren[$name])) {
                $name = $directChildren[$name];
                array_unshift($bundleMap, $this->bundles[$name]);
                $hierarchy[] = $name;
            }

            foreach ($hierarchy as $hierarchyBundle) {
                $this->bundleMap[$hierarchyBundle] = $bundleMap;
                array_pop($bundleMap);
            }
        }
    }
}