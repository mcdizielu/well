<?php

namespace WellCommerce\Bundle\GeneratorBundle\Factory;

use WellCommerce\Bundle\GeneratorBundle\Model\WellCommerceBundle;

/**
 * Class WellCommerceBundleFactory
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class WellCommerceBundleFactory
{
    public function create(string $class): WellCommerceBundle
    {
        $reflectionClass = new \ReflectionClass($class);
        $shortName       = $reflectionClass->getShortName();
        $namespace       = $reflectionClass->getNamespaceName();
        $targetDirectory = dirname($reflectionClass->getFileName());
        
        return new WellCommerceBundle($namespace, $shortName, $targetDirectory, 'yml', true);
    }
}
