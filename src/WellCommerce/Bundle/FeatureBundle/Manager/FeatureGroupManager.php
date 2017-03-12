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

namespace WellCommerce\Bundle\FeatureBundle\Manager;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;
use WellCommerce\Bundle\FeatureBundle\Entity\Feature;
use WellCommerce\Bundle\FeatureBundle\Entity\FeatureGroup;
use WellCommerce\Bundle\FeatureBundle\Entity\FeatureSet;
use WellCommerce\Bundle\FeatureBundle\Entity\ProductFeature;

/**
 * Class FeatureGroupManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureGroupManager extends AbstractManager
{
    public function getFeatureGroups(int $featureSetId, int $productId): array
    {
        $groups        = [];
        $featureSet    = $this->findFeatureSet($featureSetId);
        $featureGroups = $featureSet->getGroups();
        
        $featureGroups->map(function (FeatureGroup $group) use (&$groups, &$featureSetId, $productId) {
            $groups[] = [
                'id'       => $group->getId(),
                'set_id'   => $featureSetId,
                'name'     => $group->translate()->getName(),
                'children' => $this->getFeatures($group, $productId),
            ];
        });
        
        return $groups;
    }
    
    public function getFeatures(FeatureGroup $group, int $id): array
    {
        $attributes           = [];
        $attributesCollection = $group->getFeatures();
        
        $attributesCollection->map(function (Feature $feature) use (&$attributes, $id) {
            $attributes[] = [
                'id'    => $feature->getId(),
                'name'  => $feature->translate()->getName(),
                'type'  => $feature->getType(),
                'value' => $this->getProductFeature($feature, $id),
            ];
        });
        
        return $attributes;
    }
    
    public function findFeatureSet(int $id): FeatureSet
    {
        return $this->get('feature_set.repository')->find($id);
    }
    
    public function getProductFeature(Feature $feature, int $id): string
    {
        $product = $this->get('product.repository')->find($id);
        
        if ($product instanceof Product) {
            $productFeature = $this->get('product_feature.repository')->findOneBy([
                'feature' => $feature,
                'product' => $product,
            ]);
            
            if ($productFeature instanceof ProductFeature) {
                return $productFeature->getValue();
            }
        }
        
        return '';
    }
}
