<?php

namespace WellCommerce\Bundle\CatalogBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;

/**
 * Class ProductViewedEvent
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ProductViewedEvent extends Event
{
    const EVENT_NAME = 'front.product.viewed';
    
    /**
     * @var Product
     */
    private $product;
    
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    
    public function getProduct(): Product
    {
        return $this->product;
    }
}
