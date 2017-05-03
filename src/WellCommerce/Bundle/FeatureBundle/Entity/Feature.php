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
use WellCommerce\Extra\FeatureBundle\Entity\FeatureExtraTrait;

/**
 * Class Feature
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Feature implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use FeatureExtraTrait;
    
    /**
     * @var Collection
     */
    protected $groups;
    
    /**
     * @var int
     */
    protected $type = 1;
    
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
        $this->groups->map(function (FeatureGroup $group) use ($groups) {
            if (false === $groups->contains($group)) {
                $group->removeFeature($this);
            }
        });
        
        $groups->map(function (FeatureGroup $group) {
            if (false === $this->groups->contains($group)) {
                $group->addFeature($this);
            }
        });
    }
    
    public function addGroup(FeatureGroup $group)
    {
        $this->groups->add($group);
        $group->addFeature($this);
    }
    
    public function getType(): int
    {
        return $this->type;
    }
    
    public function setType(int $type)
    {
        $this->type = $type;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): FeatureTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
