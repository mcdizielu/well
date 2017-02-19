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

namespace WellCommerce\Bundle\CatalogBundle\Form\DataTransformer;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use WellCommerce\Bundle\CatalogBundle\Entity\Availability;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\Variant;
use WellCommerce\Bundle\CatalogBundle\Entity\VariantOption;
use WellCommerce\Bundle\CatalogBundle\Manager\VariantManager;
use WellCommerce\Bundle\CoreBundle\Form\DataTransformer\CollectionToArrayTransformer;

/**
 * Class VariantCollectionToArrayTransformer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class VariantCollectionToArrayTransformer extends CollectionToArrayTransformer
{
    /**
     * @var VariantManager
     */
    protected $variantManager;
    
    /**
     * @param VariantManager $variantManager
     */
    public function setVariantManager(VariantManager $variantManager)
    {
        $this->variantManager = $variantManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function transform($modelData)
    {
        $values = [];
        
        if ($modelData instanceof Collection) {
            $modelData->map(function (Variant $variant) use (&$values) {
                $values[] = [
                    'id'           => $variant->getId(),
                    'suffix'       => $variant->getModifierType(),
                    'modifier'     => $variant->getModifierValue(),
                    'stock'        => $variant->getStock(),
                    'symbol'       => $variant->getSymbol(),
                    'hierarchy'    => $variant->getHierarchy(),
                    'status'       => $variant->isEnabled(),
                    'weight'       => $variant->getWeight(),
                    'availability' => $this->transformAvailability($variant->getAvailability()),
                    'attributes'   => $this->transformOptions($variant->getOptions()),
                ];
            });
        }
        
        return $values;
    }
    
    private function transformAvailability(Availability $availability = null)
    {
        if (null !== $availability) {
            return $availability->getId();
        }
        
        return null;
    }
    
    public function transformOptions(Collection $collection = null): array
    {
        if (null === $collection) {
            return [];
        }
        
        $values = [];
        $collection->map(function (VariantOption $variantOption) use (&$values) {
            $values[$variantOption->getAttribute()->getId()] = $variantOption->getAttributeValue()->getId();
        });
        
        return $values;
    }
    
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($modelData, PropertyPathInterface $propertyPath, $values)
    {
        if ($modelData instanceof Product && null !== $values) {
            $collection = $this->variantManager->getAttributesCollectionForProduct($modelData, $values);
            $modelData->setVariants($collection);
        }
    }
}
