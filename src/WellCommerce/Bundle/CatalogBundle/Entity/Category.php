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

namespace WellCommerce\Bundle\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\AppBundle\Entity\ShopCollectionAwareTrait;
use WellCommerce\Bundle\CatalogBundle\Entity\Extra\CategoryExtraTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Sortable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Category
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Category implements EntityInterface
{
    use Identifiable;
    use Sortable;
    use Enableable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    use CategoryExtraTrait;
    
    protected $productsCount = 0;
    protected $childrenCount = 0;
    protected $symbol        = '';
    
    /**
     * @var null|Category
     */
    protected $parent;
    
    /**
     * @var Collection
     */
    protected $children;
    
    /**
     * @var Collection
     */
    protected $products;
    
    public function __construct()
    {
        $this->shops    = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
    }
    
    public function getSymbol(): string
    {
        return $this->symbol;
    }
    
    public function setSymbol(string $symbol)
    {
        $this->symbol = $symbol;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }
    
    public function setChildren(Collection $children)
    {
        $this->children = $children;
    }
    
    public function getChildren(): Collection
    {
        return $this->children;
    }
    
    public function addChild(Category $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
    }
    
    public function getProducts(): Collection
    {
        return $this->products;
    }
    
    public function setProducts(Collection $products)
    {
        $this->products = $products;
    }
    
    public function getProductsCount(): int
    {
        return $this->productsCount;
    }
    
    public function setProductsCount(int $productsCount)
    {
        $this->productsCount = $productsCount;
    }
    
    public function getChildrenCount(): int
    {
        return $this->childrenCount;
    }
    
    public function setChildrenCount(int $childrenCount)
    {
        $this->childrenCount = $childrenCount;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): CategoryTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
