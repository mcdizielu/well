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

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\AppBundle\Entity\Media;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Sortable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Photo
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductPhoto implements EntityInterface
{
    use Identifiable;
    use Sortable;
    use Timestampable;
    
    /**
     * @var bool
     */
    protected $mainPhoto = false;
    
    /**
     * @var Product
     */
    protected $product;
    
    /**
     * @var Media
     */
    protected $photo;
    
    public function getProduct(): Product
    {
        return $this->product;
    }
    
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
    
    public function getPhoto(): Media
    {
        return $this->photo;
    }
    
    public function setPhoto(Media $photo)
    {
        $this->photo = $photo;
    }
    
    public function isMainPhoto(): bool
    {
        return $this->mainPhoto;
    }
    
    public function setMainPhoto(bool $mainPhoto)
    {
        $this->mainPhoto = $mainPhoto;
    }
}
