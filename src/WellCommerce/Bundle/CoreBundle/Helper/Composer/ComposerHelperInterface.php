<?php


namespace WellCommerce\Bundle\CoreBundle\Helper\Composer;

use Composer\Composer;

/**
 * Interface ComposerHelperInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ComposerHelperInterface
{
    public function getPackages(): array;
    
    public function getComposer(): Composer;
}
