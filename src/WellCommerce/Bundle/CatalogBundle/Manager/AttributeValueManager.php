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
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeValue;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class AttributeValueManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AttributeValueManager extends AbstractManager
{
    public function addAttributeValue(string $attributeValueName, int $attributeId): AttributeValue
    {
        $attribute = $this->findAttribute($attributeId);
        $value     = $this->createAttributeValue($attributeValueName, $attribute);
        
        return $value;
    }
    
    protected function findAttribute(int $attributeId): Attribute
    {
        return $this->get('attribute.repository')->find($attributeId);
    }
    
    protected function createAttributeValue(string $name, Attribute $attribute): AttributeValue
    {
        /** @var $value AttributeValue */
        $value = $this->initResource();
        
        foreach ($this->getLocales() as $locale) {
            $value->translate($locale->getCode())->setName($name);
        }
        
        $value->mergeNewTranslations();
        $value->addAttribute($attribute);
        $this->createResource($value);
        
        return $value;
    }
}
