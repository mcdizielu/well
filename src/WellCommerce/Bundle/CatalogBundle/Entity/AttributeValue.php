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
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class AttributeValue
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AttributeValue implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    
    /**
     * @var Collection
     */
    protected $attributes;
    
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }
    
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }
    
    public function setAttributes(Collection $attributes)
    {
        $this->syncOldAttributes($attributes);
        $this->syncNewAttributes($attributes);
    }
    
    public function addAttribute(Attribute $attribute)
    {
        $this->attributes->add($attribute);
        $attribute->addValue($this);
    }
    
    public function translate($locale = null, $fallbackToDefault = true): AttributeValueTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
    
    private function syncOldAttributes(Collection $attributes)
    {
        $this->attributes->map(function (Attribute $attribute) use ($attributes) {
            if (false === $attributes->contains($attribute)) {
                $attribute->removeValue($this);
            }
        });
    }
    
    private function syncNewAttributes(Collection $attributes)
    {
        $attributes->map(function (Attribute $attribute) {
            if (false === $this->attributes->contains($attribute)) {
                $attribute->addValue($this);
            }
        });
    }
}
