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
use WellCommerce\Extra\FeatureBundle\Entity\FeatureGroupExtraTrait;

/**
 * Class FeatureGroup
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureGroup implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use FeatureGroupExtraTrait;
    
    /**
     * @var Collection
     */
    protected $features;
    
    /**
     * @var Collection
     */
    protected $sets;
    
    public function __construct()
    {
        $this->features = new ArrayCollection();
        $this->sets     = new ArrayCollection();
    }
    
    public function getSets(): Collection
    {
        return $this->sets;
    }
    
    public function setSets(Collection $sets)
    {
        $this->sets->map(function (FeatureSet $set) use ($sets) {
            if (false === $sets->contains($set)) {
                $set->removeGroup($this);
            }
        });
        
        $sets->map(function (FeatureSet $set) {
            if (false === $this->sets->contains($set)) {
                $set->addGroup($this);
            }
        });
    }
    
    public function addSet(FeatureSet $set)
    {
        $this->sets->add($set);
        $set->addGroup($this);
    }
    
    public function getFeatures(): Collection
    {
        return $this->features;
    }
    
    public function setFeatures(Collection $features)
    {
        if ($this->features instanceof Collection) {
            $this->features->map(function (Feature $feature) use ($features) {
                if (false === $features->contains($feature)) {
                    $this->removeFeature($feature);
                }
            });
        }
        
        $this->features = $features;
    }
    
    public function removeFeature(Feature $feature)
    {
        $this->features->removeElement($feature);
    }
    
    public function addFeature(Feature $feature)
    {
        $this->features->add($feature);
    }
    
    public function translate($locale = null, $fallbackToDefault = true): FeatureGroupTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
