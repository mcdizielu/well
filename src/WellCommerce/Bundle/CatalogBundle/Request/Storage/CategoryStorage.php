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
 * Class CategoryStorage
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class CategoryStorage implements CategoryStorageInterface
{
    private $currentCategory;

    public function setCurrentCategory(Category $category)
    {
        $this->currentCategory = $category;
    }

    public function getCurrentCategory() : Category
    {
        return $this->currentCategory;
    }

    public function getCurrentCategoryIdentifier() : int
    {
        if ($this->hasCurrentCategory()) {
            return $this->getCurrentCategory()->getId();
        }

        return 0;
    }

    public function hasCurrentCategory() : bool
    {
        return $this->currentCategory instanceof Category;
    }
}
