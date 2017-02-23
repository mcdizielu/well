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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\AppBundle\Entity\ShopCollectionAwareTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Sortable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Page
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Page implements EntityInterface
{
    use Identifiable;
    use Sortable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    
    protected $publish       = true;
    protected $section       = '';
    protected $redirectType  = 1;
    protected $redirectUrl   = '';
    protected $redirectRoute = '';
    
    /**
     * @var Page|null
     */
    protected $parent;
    
    /**
     * @var Collection
     */
    protected $clientGroups;
    
    /**
     * @var Collection
     */
    protected $children;
    
    public function __construct()
    {
        $this->clientGroups = new ArrayCollection();
        $this->shops        = new ArrayCollection();
    }
    
    public function getPublish(): bool
    {
        return $this->publish;
    }
    
    public function setPublish(bool $publish)
    {
        $this->publish = $publish;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function setParent(self $parent = null)
    {
        $this->parent = $parent;
    }
    
    public function getChildren(): Collection
    {
        return $this->children;
    }
    
    public function addChild(self $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
    }
    
    public function getRedirectRoute()
    {
        return $this->redirectRoute;
    }
    
    public function setRedirectRoute($redirectRoute)
    {
        $this->redirectRoute = $redirectRoute;
    }
    
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }
    
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }
    
    public function getClientGroups(): Collection
    {
        return $this->clientGroups;
    }
    
    public function setClientGroups(Collection $clientGroups)
    {
        $this->clientGroups = $clientGroups;
    }
    
    public function prePersist()
    {
        $redirectType = $this->getRedirectType();
        switch ($redirectType) {
            case 0:
                $this->setRedirectRoute(null);
                $this->setRedirectUrl(null);
                break;
            case 1:
                $this->setRedirectRoute(null);
                break;
            case 2:
                $this->setRedirectUrl(null);
                break;
        }
    }
    
    public function getRedirectType(): int
    {
        return $this->redirectType;
    }
    
    public function setRedirectType(int $redirectType)
    {
        $this->redirectType = $redirectType;
    }
    
    public function getSection(): string
    {
        return $this->section;
    }
    
    public function setSection(string $section)
    {
        $this->section = $section;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): PageTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
