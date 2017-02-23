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

namespace WellCommerce\Bundle\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Sortable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class AdminMenu
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AdminMenu implements EntityInterface
{
    use Identifiable;
    use Sortable;
    
    protected $identifier = '';
    protected $name       = '';
    protected $routeName  = '';
    protected $cssClass   = '';
    
    /**
     * @var null|AdminMenu
     */
    protected $parent;
    
    /**
     * @var Collection
     */
    protected $children;
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    
    public function getIdentifier(): string
    {
        return $this->identifier;
    }
    
    public function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function setParent(AdminMenu $parent = null)
    {
        $this->parent = $parent;
    }
    
    public function setChildren(Collection $children)
    {
        $this->children = $children;
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
    
    public function getRouteName(): string
    {
        return $this->routeName;
    }
    
    public function setRouteName(string $routeName)
    {
        $this->routeName = $routeName;
    }
    
    public function getCssClass(): string
    {
        return $this->cssClass;
    }
    
    public function setCssClass(string $cssClass)
    {
        $this->cssClass = $cssClass;
    }
}
