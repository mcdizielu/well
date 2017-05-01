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

namespace WellCommerce\Bundle\AlsoPurchasedBundle;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\CoreBundle\HttpKernel\AbstractWellCommerceBundle;

/**
 * Class WellCommerceAlsoPurchasedBundle
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class WellCommerceAlsoPurchasedBundle extends AbstractWellCommerceBundle
{
    public static function registerBundles(Collection $bundles, string $environment)
    {
        $bundles->add(new self());
    }
}
