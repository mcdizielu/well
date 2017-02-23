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

use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class VariantOption
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class VariantOption implements EntityInterface
{
    use Identifiable;
    
    /**
     * @var Variant
     */
    protected $variant;
    
    /**
     * @var Attribute
     */
    protected $attribute;
    
    /**
     * @var AttributeValue
     */
    protected $attributeValue;
    
    public function getVariant(): Variant
    {
        return $this->variant;
    }
    
    public function setVariant(Variant $variant)
    {
        $this->variant = $variant;
    }
    
    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }
    
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }
    
    public function getAttributeValue(): AttributeValue
    {
        return $this->attributeValue;
    }
    
    public function setAttributeValue(AttributeValue $attributeValue)
    {
        $this->attributeValue = $attributeValue;
    }
}
