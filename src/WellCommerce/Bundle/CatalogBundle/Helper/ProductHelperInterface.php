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

use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;

/**
 * Interface ProductHelperInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ProductHelperInterface
{
    /**
     * Returns the default product's template variables
     *
     * @param Product $product
     *
     * @return array
     */
    public function getProductDefaultTemplateData(Product $product): array;
    
    /**
     * Returns a dataset of products recommended for category
     *
     * @param Category $category
     *
     * @return array
     */
    public function getProductRecommendationsForCategory(Category $category): array;
}
