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

use WellCommerce\Bundle\CatalogBundle\Entity\ProductStatus;

/**
 * Class ProductStatusStorage
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ProductStatusStorage implements ProductStatusStorageInterface
{
    /**
     * @var ProductStatus
     */
    private $currentProductStatus;
    
    public function setCurrentProductStatus(ProductStatus $productStatus)
    {
        $this->currentProductStatus = $productStatus;
    }
    
    public function getCurrentProductStatus(): ProductStatus
    {
        return $this->currentProductStatus;
    }
    
    public function getCurrentProductStatusIdentifier(): int
    {
        return $this->getCurrentProductStatus()->getId();
    }
    
    public function hasCurrentProductStatus(): bool
    {
        return $this->currentProductStatus instanceof ProductStatus;
    }
}
