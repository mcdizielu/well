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

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class ProductFeature
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductFeature implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    
    /**
     * @var string
     */
    protected $value = '';
    
    /**
     * @var Feature
     */
    protected $feature;
    
    /**
     * @var Product
     */
    protected $product;
    
    public function getValue(): string
    {
        return $this->value;
    }
    
    public function setValue(string $value)
    {
        $this->value = $value;
    }
    
    public function getProduct(): Product
    {
        return $this->product;
    }
    
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
    
    public function getFeature(): Feature
    {
        return $this->feature;
    }
    
    public function setFeature(Feature $feature)
    {
        $this->feature = $feature;
    }
}
