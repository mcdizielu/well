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
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class UserGroup
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class UserGroup implements EntityInterface
{
    use Identifiable;
    use Enableable;
    use Blameable;
    
    protected $name = '';
    
    /**
     * @var Collection
     */
    protected $users;
    
    /**
     * @var Collection
     */
    protected $permissions;
    
    public function __construct()
    {
        $this->users       = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function getUsers(): Collection
    {
        return $this->users;
    }
    
    public function setUsers(Collection $users)
    {
        $this->users = $users;
    }
    
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }
    
    public function setPermissions(Collection $permissions)
    {
        $this->permissions = $permissions;
    }
}
