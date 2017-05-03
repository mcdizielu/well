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

namespace WellCommerce\Bundle\FeatureBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Extra\FeatureBundle\Entity\FeatureSetExtraTrait;

/**
 * Class FeatureSet
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureSet implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use FeatureSetExtraTrait;
    
    /**
     * @var Collection
     */
    protected $groups;
    
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }
    
    public function getGroups(): Collection
    {
        return $this->groups;
    }
    
    public function setGroups(Collection $groups)
    {
        if ($this->groups instanceof Collection) {
            $this->groups->map(function (FeatureGroup $group) use ($groups) {
                if (false === $groups->contains($group)) {
                    $this->removeGroup($group);
                }
            });
        }
        
        $this->groups = $groups;
    }
    
    public function removeGroup(FeatureGroup $group)
    {
        $this->groups->removeElement($group);
    }
    
    public function addGroup(FeatureGroup $group)
    {
        $this->groups->add($group);
    }
    
    public function translate($locale = null, $fallbackToDefault = true): FeatureSetTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
