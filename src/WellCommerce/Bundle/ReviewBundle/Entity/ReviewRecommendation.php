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
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Review
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ReviewRecommendation implements EntityInterface
{
    use Identifiable;
    use Enableable;
    use Timestampable;
    
    protected $liked   = false;
    protected $unliked = false;
    
    /**
     * @var Review
     */
    protected $review;
    
    public function getLiked(): bool
    {
        return $this->liked;
    }
    
    public function setLiked(bool $liked)
    {
        $this->liked = $liked;
    }
    
    public function setUnliked(bool $unliked)
    {
        $this->unliked = $unliked;
    }
    
    public function getUnliked(): bool
    {
        return $this->unliked;
    }
    
    public function getReview(): Review
    {
        return $this->review;
    }
    
    public function setReview(Review $review)
    {
        $this->review = $review;
    }
}
