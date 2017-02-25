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

use WellCommerce\Bundle\CoreBundle\HttpKernel\WellCommerceKernel;

/**
 * Class AppKernel
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AppKernel extends WellCommerceKernel
{
    public function registerBundles()
    {
        $bundles = [
            new \WellCommerce\Bundle\ApiBundle\WellCommerceApiBundle(),
            new \WellCommerce\Bundle\AppBundle\WellCommerceAppBundle(),
            new \WellCommerce\Bundle\CoreBundle\WellCommerceCoreBundle(),
            new \WellCommerce\Bundle\SearchBundle\WellCommerceSearchBundle(),
            new \WellCommerce\Bundle\GeneratorBundle\WellCommerceGeneratorBundle(),
            new \WellCommerce\Bundle\OrderBundle\WellCommerceOrderBundle(),
            new \WellCommerce\Bundle\CatalogBundle\WellCommerceCatalogBundle(),
            new \WellCommerce\Bundle\CouponBundle\WellCommerceCouponBundle(),
            new \WellCommerce\Bundle\OAuthBundle\WellCommerceOAuthBundle(),
            new \WellCommerce\Bundle\CmsBundle\WellCommerceCmsBundle(),
            new \WellCommerce\Bundle\ReviewBundle\WellCommerceReviewBundle(),
            new \WellCommerce\Bundle\ShowcaseBundle\WellCommerceShowcaseBundle(),
            new \WellCommerce\Bundle\WishlistBundle\WellCommerceWishlistBundle(),
        ];
        
        return array_merge(parent::registerBundles(), $bundles);
    }
}
