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

namespace WellCommerce\Bundle\CmsBundle\Entity;

use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\AppBundle\Entity\Media;
use WellCommerce\Bundle\AppBundle\Entity\ShopCollectionAwareTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class News
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class News implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    
    protected $publish  = true;
    protected $featured = false;
    protected $startDate;
    protected $endDate;
    
    /**
     * @var Media
     */
    protected $photo;
    
    public function __construct()
    {
        $this->startDate = Carbon::now();
        $this->endDate   = Carbon::now()->addMonth(1);
        $this->shops     = new ArrayCollection();
    }
    
    public function getPublish(): bool
    {
        return $this->publish;
    }
    
    public function setPublish(bool $publish)
    {
        $this->publish = $publish;
    }
    
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }
    
    public function setStartDate(DateTime $startDate)
    {
        $this->startDate = $startDate;
    }
    
    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }
    
    public function setEndDate(DateTime $endDate)
    {
        $this->endDate = $endDate;
    }
    
    public function getFeatured(): bool
    {
        return $this->featured;
    }
    
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }
    
    public function getPhoto()
    {
        return $this->photo;
    }
    
    public function setPhoto(Media $photo = null)
    {
        $this->photo = $photo;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): NewsTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
