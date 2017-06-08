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

namespace WellCommerce\Bundle\StandardEditionBundle;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\CoreBundle\HttpKernel\AbstractWellCommerceBundle;

/**
 * Class WellCommerceStandardEditionBundle
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class WellCommerceStandardEditionBundle extends AbstractWellCommerceBundle
{
    public static function registerBundles(Collection $bundles, string $environment)
    {
        \WellCommerce\Bundle\CoreBundle\WellCommerceCoreBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\AlsoPurchasedBundle\WellCommerceAlsoPurchasedBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\ApiBundle\WellCommerceApiBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\AppBundle\WellCommerceAppBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\SearchBundle\WellCommerceSearchBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\GeneratorBundle\WellCommerceGeneratorBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\OrderBundle\WellCommerceOrderBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\InvoiceBundle\WellCommerceInvoiceBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\LastViewedBundle\WellCommerceLastViewedBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\CatalogBundle\WellCommerceCatalogBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\CouponBundle\WellCommerceCouponBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\OAuthBundle\WellCommerceOAuthBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\CmsBundle\WellCommerceCmsBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\ReviewBundle\WellCommerceReviewBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\ShowcaseBundle\WellCommerceShowcaseBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\SimilarProductBundle\WellCommerceSimilarProductBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\WishlistBundle\WellCommerceWishlistBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\FeatureBundle\WellCommerceFeatureBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\TemplateEditorBundle\WellCommerceTemplateEditorBundle::registerBundles($bundles, $environment);
        \WellCommerce\Bundle\ShipmentBundle\WellCommerceShipmentBundle::registerBundles($bundles, $environment);
        
        $bundles->add(new self);
    }
}
