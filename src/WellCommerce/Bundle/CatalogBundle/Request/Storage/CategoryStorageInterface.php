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

use WellCommerce\Bundle\CatalogBundle\Entity\Category;

/**
 * Interface CategoryContextInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface CategoryStorageInterface
{
    public function setCurrentCategory(Category $category);
    
    public function getCurrentCategory(): Category;
    
    public function getCurrentCategoryIdentifier(): int;
    
    public function hasCurrentCategory(): bool;
}
