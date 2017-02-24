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

namespace WellCommerce\Bundle\CatalogBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\CatalogBundle\Entity\Attribute;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeValue;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\Variant;
use WellCommerce\Bundle\CatalogBundle\Entity\VariantOption;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class VariantManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class VariantManager extends AbstractManager
{
    public function getAttributesCollectionForProduct(Product $product, array $values): Collection
    {
        $values     = $this->filterValues($values);
        $collection = new ArrayCollection();
        
        foreach ($values as $id => $value) {
            $variant = $this->getVariant($id, $value);
            $variant->setProduct($product);
            $collection->add($variant);
        }
        
        return $collection;
    }
    
    protected function getVariant($id, $value): Variant
    {
        /** @var $variant Variant */
        $variant = $this->repository->find($id);
        if (null === $variant) {
            $variant = $this->initResource();
        }
        
        $variantOptions = $this->makeVariantOptionCollection($variant, $value['attributes']);
        
        $variant->setModifierType($value['suffix']);
        $variant->setModifierValue($value['modifier']);
        $variant->setStock($value['stock']);
        $variant->setSymbol($value['symbol']);
        $variant->setWeight($value['weight']);
        $variant->setHierarchy($value['hierarchy']);
        $variant->setOptions($variantOptions);
        
        return $variant;
    }
    
    protected function makeVariantOptionCollection(Variant $variant, $values): Collection
    {
        $collection = new ArrayCollection();
        foreach ($values as $attributeId => $attributeValueId) {
            $attribute      = $this->getAttribute($attributeId);
            $attributeValue = $this->getAttributeValue($attributeValueId);
            $variantOption  = $this->getVariantOption($variant, $attribute, $attributeValue);
            $collection->add($variantOption);
        }
        
        return $collection;
    }
    
    protected function getVariantOption(Variant $variant, Attribute $attribute, AttributeValue $attributeValue): VariantOption
    {
        $variantOption = $this->findVariantOption($variant, $attribute, $attributeValue);
        
        if (!$variantOption instanceof VariantOption) {
            $variantOption = new VariantOption();
            $variantOption->setVariant($variant);
            $variantOption->setAttribute($attribute);
            $variantOption->setAttributeValue($attributeValue);
        }
        
        return $variantOption;
    }
    
    protected function findVariantOption(Variant $variant, Attribute $attribute, AttributeValue $attributeValue)
    {
        return $this->get('variant_option.repository')->findOneBy([
            'variant'        => $variant,
            'attribute'      => $attribute,
            'attributeValue' => $attributeValue,
        ]);
    }
    
    protected function getAttribute(int $id): Attribute
    {
        return $this->get('attribute.repository')->find($id);
    }
    
    protected function getAttributeValue(int $id): AttributeValue
    {
        return $this->get('attribute_value.repository')->find($id);
    }
    
    private function filterValues(array $values): array
    {
        return array_filter($values, function ($value) {
            return is_array($value);
        });
    }
}
