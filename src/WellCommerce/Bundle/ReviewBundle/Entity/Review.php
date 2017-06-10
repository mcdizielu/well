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

namespace WellCommerce\Bundle\ReviewBundle\Entity;

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Extra\ReviewBundle\Entity\ReviewExtraTrait;

/**
 * Class Review
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Review implements EntityInterface
{
    use Identifiable;
    use Enableable;
    use Timestampable;
    use ReviewExtraTrait;
    
    protected $nick   = '';
    protected $review = '';
    protected $rating = 5;
    
    /**
     * @var Product
     */
    protected $product;
    
    public function getNick(): string
    {
        return $this->nick;
    }
    
    public function setNick(string $nick)
    {
        $this->nick = $nick;
    }
    
    public function getReview(): string
    {
        return $this->review;
    }
    
    public function setReview(string $review)
    {
        $this->review = $review;
    }
    
    public function getRating(): int
    {
        return $this->rating;
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
