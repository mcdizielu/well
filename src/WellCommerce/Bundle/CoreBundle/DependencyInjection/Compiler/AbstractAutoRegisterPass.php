<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\ClassFinder;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\ServiceIdGenerator;

/**
 * Class AbstractAutoRegisterPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractAutoRegisterPass implements CompilerPassInterface
{
    /**
     * @var BundleInterface
     */
    protected $bundle;
    
    /**
     * @var ClassFinder
     */
    protected $classFinder;
    
    /**
     * @var ServiceIdGenerator
     */
    protected $serviceIdGenerator;
    
    public function __construct(BundleInterface $bundle)
    {
        $this->bundle             = $bundle;
        $this->classFinder        = new ClassFinder();
        $this->serviceIdGenerator = new ServiceIdGenerator();
    }
    
    protected function createReference(string $baseName, string $type, ContainerBuilder $container)
    {
        $serviceId = $this->serviceIdGenerator->getServiceId($baseName, $type);
        
        return $container->has($serviceId) ? new Reference($serviceId) : null;
    }
}
