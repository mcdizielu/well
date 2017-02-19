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
use WellCommerce\Bundle\AppBundle\Entity\Media;
use WellCommerce\Bundle\AppBundle\Entity\ShopCollectionAwareTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Producer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Producer implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    
    /**
     * @var Media
     */
    protected $photo;
    
    /**
     * @var Collection
     */
    protected $products;
    
    /**
     * @var Collection
     */
    protected $deliverers;
    
    public function __construct()
    {
        $this->products   = new ArrayCollection();
        $this->deliverers = new ArrayCollection();
        $this->shops      = new ArrayCollection();
    }
    
    public function getProducts(): Collection
    {
        return $this->products;
    }
    
    public function getDeliverers(): Collection
    {
        return $this->deliverers;
    }
    
    public function setDeliverers(Collection $collection)
    {
        $this->deliverers = $collection;
    }
    
    public function getPhoto()
    {
        return $this->photo;
    }
    
    public function setPhoto(Media $photo = null)
    {
        $this->photo = $photo;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): ProducerTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
