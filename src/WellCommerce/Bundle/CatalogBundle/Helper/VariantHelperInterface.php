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

namespace WellCommerce\Bundle\CatalogBundle\Helper;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\Variant;

/**
 * Interface VariantHelperInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface VariantHelperInterface
{
    public function getVariants(Product $product): array;
    
    public function getAttributes(Product $product): array;
    
    public function getVariantOptions(Variant $variant): array;
}
