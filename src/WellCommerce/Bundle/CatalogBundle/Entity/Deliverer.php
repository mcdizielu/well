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
 * Class Deliverer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Deliverer implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    
    /**
     * @var Collection
     */
    protected $producers;
    
    public function __construct()
    {
        $this->producers = new ArrayCollection();
    }
    
    public function getProducers(): Collection
    {
        return $this->producers;
    }
    
    public function setProducers(Collection $collection)
    {
        $this->producers = $collection;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): DelivererTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
