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

use Carbon\Carbon;
use DateTime;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class ProductDistinction
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductDistinction implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    
    /**
     * @var null|DateTime
     */
    protected $validFrom = null;
    
    /**
     * @var null|DateTime
     */
    protected $validTo = null;
    
    /**
     * @var Product
     */
    protected $product;
    
    /**
     * @var ProductStatus
     */
    protected $status;
    
    public function getValidFrom()
    {
        return $this->validFrom;
    }
    
    public function setValidFrom(DateTime $validFrom = null)
    {
        if (null !== $validFrom) {
            $validFrom = $validFrom->setTime(0, 0, 0);
        }
        
        $this->validFrom = $validFrom;
    }
    
    public function getValidTo()
    {
        return $this->validTo;
    }
    
    public function setValidTo(DateTime $validTo = null)
    {
        if (null !== $validTo) {
            $validTo = $validTo->setTime(23, 59, 59);
        }
        
        $this->validTo = $validTo;
    }
    
    public function getProduct(): Product
    {
        return $this->product;
    }
    
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
    
    public function setStatus(ProductStatus $status)
    {
        $this->status = $status;
    }
    
    public function getStatus(): ProductStatus
    {
        return $this->status;
    }
    
    public function isValid(): bool
    {
        $validFrom = ($this->validFrom === null) ? Carbon::now()->startOfDay() : Carbon::instance($this->validFrom)->startOfDay();
        $validTo   = ($this->validTo === null) ? Carbon::now()->endOfDay() : Carbon::instance($this->validTo)->endOfDay();
        
        return $validFrom->isPast() && $validTo->isFuture();
    }
}
