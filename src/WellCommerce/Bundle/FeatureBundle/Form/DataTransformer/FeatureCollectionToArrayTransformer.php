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

namespace WellCommerce\Bundle\FeatureBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Form\DataTransformer\CollectionToArrayTransformer;
use WellCommerce\Bundle\FeatureBundle\Entity\Feature;
use WellCommerce\Bundle\FeatureBundle\Entity\ProductFeature;

/**
 * Class FeatureCollectionToArrayTransformer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureCollectionToArrayTransformer extends CollectionToArrayTransformer
{
    public function transform($modelData)
    {
    }
    
    public function reverseTransform($modelData, PropertyPathInterface $propertyPath, $values)
    {
        if ($modelData instanceof Product && null !== $values) {
            $collection = $this->getProductFeatureCollection($modelData, $values);
            $modelData->setFeatures($collection);
        }
    }
    
    public function getProductFeatureCollection(Product $product, array $values): Collection
    {
        $values     = $this->filterValues($values);
        $collection = new ArrayCollection();
        $groups     = $values['groups'];
        
        foreach ($groups as $index => $groupData) {
            foreach ($groupData['features'] as $featureData) {
                $feature        = $this->getFeature($featureData['id']);
                $productFeature = $this->findProductFeature($feature, $product);
                if (!$productFeature instanceof ProductFeature) {
                    $productFeature = new ProductFeature();
                    $productFeature->setProduct($product);
                    $productFeature->setFeature($feature);
                }
                $productFeature->setValue(strval($featureData['value']));
                $collection->add($productFeature);
            }
        }
        
        return $collection;
    }
    
    private function getFeature(int $id): Feature
    {
        return $this->getDoctrineHelper()->getEntityManager()->getRepository(Feature::class)->find($id);
    }
    
    public function findProductFeature(Feature $feature, Product $product)
    {
        return $this->getRepository()->findOneBy([
            'feature' => $feature,
            'product' => $product,
        ]);
    }
    
    private function filterValues(array $values): array
    {
        return array_filter($values, function ($value) {
            return is_array($value);
        });
    }
}
