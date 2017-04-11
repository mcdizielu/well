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
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Bundle\AppBundle\Entity\Media;
use WellCommerce\Bundle\AppBundle\Entity\ShopCollectionAwareTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;


/**
 * Class ProducerCollection
 *
 * @author  Rafał Martonik <rafal@wellcommerce.org>
 */
class ProducerCollection implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    
    /**
     * @var Collection
     */
    protected $products;
    
    /**
     * @var Media
     */
    protected $photo;
    
    /**
     * @var Producer
     */
    protected $producer;
    
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->shops    = new ArrayCollection();
    }
    
    public function getProducts(): Collection
    {
        return $this->products;
    }
    
    public function getPhoto()
    {
        return $this->photo;
    }
    
    public function setPhoto(Media $photo = null)
    {
        $this->photo = $photo;
    }
    
    public function getProducer()
    {
        return $this->producer;
    }
    
    public function setProducer(Producer $producer = null)
    {
        $this->producer = $producer;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): ProducerCollectionTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
    
}
