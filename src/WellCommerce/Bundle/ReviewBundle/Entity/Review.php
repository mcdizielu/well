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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

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
    
    protected $nick                 = '';
    protected $review               = '';
    protected $rating               = 5;
    protected $ratingLevel          = 5;
    protected $ratingRecommendation = 5;
    protected $ratio                = 0.00;
    protected $likes                = 0;
    
    /**
     * @var Product
     */
    protected $product;
    
    /**
     * @var Collection
     */
    protected $recommendations;
    
    public function __construct()
    {
        $this->recommendations = new ArrayCollection();
    }
    
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
    
    public function setRating(int $rating)
    {
        $this->rating = $rating;
    }
    
    public function getRatingLevel(): int
    {
        return $this->ratingLevel;
    }
    
    public function setRatingLevel(int $ratingLevel)
    {
        $this->ratingLevel = $ratingLevel;
    }
    
    public function getRatio(): float
    {
        return $this->ratio;
    }
    
    public function setRatio(float $ratio)
    {
        $this->ratio = $ratio;
    }
    
    public function getLikes(): int
    {
        return $this->likes;
    }
    
    public function setLikes(int $likes)
    {
        $this->likes = $likes;
    }
    
    public function getProduct(): Product
    {
        return $this->product;
    }
    
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
    
    public function getRatingRecommendation(): int
    {
        return $this->ratingRecommendation;
    }
    
    public function setRatingRecommendation(int $ratingRecommendation)
    {
        $this->ratingRecommendation = $ratingRecommendation;
    }
    
    public function getRecommendations(): Collection
    {
        return $this->recommendations;
    }
    
    public function setRecommendations(Collection $recommendations)
    {
        $this->recommendations = $recommendations;
    }
    
    public function addRecommendation(ReviewRecommendation $recommendation)
    {
        $this->recommendations->add($recommendation);
    }
}
