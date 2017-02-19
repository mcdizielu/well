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

namespace WellCommerce\Bundle\CatalogBundle\Request\Storage;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;

/**
 * Class ProductStorage
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductStorage implements ProductStorageInterface
{
    /**
     * @var Product
     */
    protected $currentProduct;
    
    public function setCurrentProduct(Product $product)
    {
        $this->currentProduct = $product;
    }
    
    public function getCurrentProduct(): Product
    {
        return $this->currentProduct;
    }
    
    public function hasCurrentProduct(): bool
    {
        return $this->currentProduct instanceof Product;
    }
}
