<?php

namespace WellCommerce\Bundle\CoreBundle\HttpKernel;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Interface BundleInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface WellCommerceBundleInterface extends BundleInterface
{
    public function install(KernelInterface $kernel);
    
    public function update(KernelInterface $kernel);
    
    public function remove(KernelInterface $kernel);
}
