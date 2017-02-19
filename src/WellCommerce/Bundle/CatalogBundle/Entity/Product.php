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
use WellCommerce\Bundle\AppBundle\Entity\Dimension;
use WellCommerce\Bundle\AppBundle\Entity\DiscountablePrice;
use WellCommerce\Bundle\AppBundle\Entity\Media;
use WellCommerce\Bundle\AppBundle\Entity\Price;
use WellCommerce\Bundle\AppBundle\Entity\ShopCollectionAwareTrait;
use WellCommerce\Bundle\AppBundle\Entity\Tax;
use WellCommerce\Bundle\CatalogBundle\Entity\Extra\ProductExtraTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Sortable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Product
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Product implements EntityInterface
{
    use Identifiable;
    use Enableable;
    use Sortable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    use ProductExtraTrait;
    
    protected $sku         = '';
    protected $stock       = 0;
    protected $trackStock  = true;
    protected $weight      = 0.00;
    protected $packageSize = 1.00;
    
    /**
     * @var Price
     */
    protected $buyPrice;
    
    /**
     * @var Tax
     */
    protected $buyPriceTax;
    
    /**
     * @var DiscountablePrice
     */
    protected $sellPrice;
    
    /**
     * @var Tax
     */
    protected $sellPriceTax;
    
    /**
     * @var Dimension
     */
    protected $dimension;
    
    /**
     * @var AttributeGroup
     */
    protected $attributeGroup;
    
    /**
     * @var Collection
     */
    protected $variants;
    
    /**
     * @var Collection
     */
    protected $categories;
    
    /**
     * @var Collection
     */
    protected $distinctions;
    
    /**
     * @var Media
     */
    protected $photo;
    
    /**
     * @var Collection
     */
    protected $productPhotos;
    
    /**
     * @var null|Availability
     */
    protected $availability;
    
    /**
     * @var Producer
     */
    protected $producer;
    
    /**
     * @var Unit
     */
    protected $unit;
    
    public function __construct()
    {
        $this->categories    = new ArrayCollection();
        $this->productPhotos = new ArrayCollection();
        $this->distinctions  = new ArrayCollection();
        $this->variants      = new ArrayCollection();
        $this->shops         = new ArrayCollection();
        $this->sellPrice     = new DiscountablePrice();
        $this->buyPrice      = new Price();
        $this->dimension     = new Dimension();
    }
    
    public function getSku(): string
    {
        return $this->sku;
    }
    
    public function setSku(string $sku)
    {
        $this->sku = $sku;
    }
    
    public function getStock(): int
    {
        return $this->stock;
    }
    
    public function setStock(int $stock)
    {
        $this->stock = $stock;
    }
    
    public function getTrackStock(): bool
    {
        return $this->trackStock;
    }
    
    public function setTrackStock(bool $trackStock)
    {
        $this->trackStock = $trackStock;
    }
    
    public function getDistinctions(): Collection
    {
        return $this->distinctions;
    }
    
    public function setDistinctions(Collection $distinctions)
    {
        $this->distinctions->map(function (ProductDistinction $distinction) use ($distinctions) {
            if (false === $distinctions->contains($distinction)) {
                $this->distinctions->removeElement($distinction);
            }
        });
        
        $this->distinctions = $distinctions;
    }
    
    public function getPhoto()
    {
        return $this->photo;
    }
    
    public function setPhoto(Media $photo = null)
    {
        $this->photo = $photo;
    }
    
    public function getProductPhotos(): Collection
    {
        return $this->productPhotos;
    }
    
    public function setProductPhotos(Collection $photos)
    {
        $this->productPhotos = $photos;
    }
    
    public function addProductPhoto(ProductPhoto $photo)
    {
        $this->productPhotos[] = $photo;
    }
    
    public function getCategories(): Collection
    {
        return $this->categories;
    }
    
    public function setCategories(Collection $collection)
    {
        $this->categories = $collection;
    }
    
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }
    
    public function getSellPrice(): DiscountablePrice
    {
        return $this->sellPrice;
    }
    
    public function setSellPrice(DiscountablePrice $sellPrice)
    {
        $this->sellPrice = $sellPrice;
    }
    
    public function getBuyPrice(): Price
    {
        return $this->buyPrice;
    }
    
    public function setBuyPrice(Price $buyPrice)
    {
        $this->buyPrice = $buyPrice;
    }
    
    public function getWeight(): float
    {
        return $this->weight;
    }
    
    public function setWeight(float $weight)
    {
        $this->weight = $weight;
    }
    
    public function getDimension(): Dimension
    {
        return $this->dimension;
    }
    
    public function setDimension(Dimension $dimension)
    {
        $this->dimension = $dimension;
    }
    
    public function getPackageSize(): float
    {
        return $this->packageSize;
    }
    
    public function setPackageSize(float $packageSize)
    {
        $this->packageSize = $packageSize;
    }
    
    public function getAttributeGroup()
    {
        return $this->attributeGroup;
    }
    
    public function setAttributeGroup(AttributeGroup $group = null)
    {
        $this->attributeGroup = $group;
    }
    
    public function getVariants(): Collection
    {
        return $this->variants;
    }
    
    public function setVariants(Collection $variants)
    {
        $this->variants->map(function (Variant $variant) use ($variants) {
            if (false === $variants->contains($variant)) {
                $this->variants->removeElement($variant);
            }
        });
        
        $this->variants = $variants;
    }
    
    public function getBuyPriceTax()
    {
        return $this->buyPriceTax;
    }
    
    public function setBuyPriceTax(Tax $tax = null)
    {
        $this->buyPriceTax = $tax;
    }
    
    public function getSellPriceTax()
    {
        return $this->sellPriceTax;
    }
    
    public function setSellPriceTax(Tax $tax = null)
    {
        $this->sellPriceTax = $tax;
    }
    
    public function getAvailability()
    {
        return $this->availability;
    }
    
    public function setAvailability(Availability $availability = null)
    {
        $this->availability = $availability;
    }
    
    public function getProducer()
    {
        return $this->producer;
    }
    
    public function setProducer(Producer $producer)
    {
        $this->producer = $producer;
    }
    
    public function getUnit()
    {
        return $this->unit;
    }
    
    public function setUnit(Unit $unit = null)
    {
        $this->unit = $unit;
    }
}
