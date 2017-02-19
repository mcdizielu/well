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

use WellCommerce\Bundle\CatalogBundle\Entity\Attribute;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeGroup;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class AttributeManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AttributeManager extends AbstractManager
{
    public function createAttribute(string $attributeName, int $attributeGroupId): Attribute
    {
        /** @var $attribute Attribute */
        $attribute = $this->initResource();
        $group     = $this->findAttributeGroup($attributeGroupId);
        foreach ($this->getLocales() as $locale) {
            $attribute->translate($locale->getCode())->setName($attributeName);
        }
        $attribute->addGroup($group);
        $attribute->mergeNewTranslations();
        $this->createResource($attribute);
        
        return $attribute;
    }
    
    public function findAttributeGroup(int $id): AttributeGroup
    {
        return $this->get('attribute_group.repository')->find($id);
    }
}
