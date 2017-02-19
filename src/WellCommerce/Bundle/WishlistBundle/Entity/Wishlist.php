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
namespace WellCommerce\Bundle\WishlistBundle\Entity;

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Wishlist
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Wishlist implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    
    /**
     * @var Client
     */
    protected $client;
    
    /**
     * @var Product
     */
    protected $product;
    
    public function getClient(): Client
    {
        return $this->client;
    }
    
    public function setClient(Client $client)
    {
        $this->client = $client;
    }
    
    public function getProduct(): Product
    {
        return $this->product;
    }
    
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
}
