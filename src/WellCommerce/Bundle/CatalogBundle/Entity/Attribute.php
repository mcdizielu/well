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
 * Class Attribute
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Attribute implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    
    /**
     * @var Collection
     */
    protected $groups;
    
    /**
     * @var Collection
     */
    protected $values;
    
    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->values = new ArrayCollection();
    }
    
    public function getGroups(): Collection
    {
        return $this->groups;
    }
    
    public function setGroups(Collection $groups)
    {
        $this->groups->map(function (AttributeGroup $group) use ($groups) {
            if (false === $groups->contains($group)) {
                $group->removeAttribute($this);
            }
        });
        
        $groups->map(function (AttributeGroup $group) {
            if (false === $this->groups->contains($group)) {
                $group->addAttribute($this);
            }
        });
    }
    
    public function addGroup(AttributeGroup $group)
    {
        $this->groups->add($group);
        $group->addAttribute($this);
    }
    
    public function getValues(): Collection
    {
        return $this->values;
    }
    
    public function setValues(Collection $collection)
    {
        $this->values = $collection;
    }
    
    public function removeValue(AttributeValue $value)
    {
        $this->values->removeElement($value);
    }
    
    public function addValue(AttributeValue $value)
    {
        $this->values->add($value);
    }
    
    public function translate($locale = null, $fallbackToDefault = true): AttributeTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
