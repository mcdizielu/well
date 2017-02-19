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

namespace WellCommerce\Bundle\AppBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\AppBundle\Entity\Role;
use WellCommerce\Bundle\AppBundle\Entity\User;
use WellCommerce\Bundle\AppBundle\Entity\UserGroup;
use WellCommerce\Bundle\AppBundle\Entity\UserGroupPermission;
use WellCommerce\Bundle\DoctrineBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadUserData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadUserData extends AbstractDataFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $role = new Role();
        $role->setName('admin');
        $role->setRole('ROLE_ADMIN');
        $manager->persist($role);
        
        $this->setReference('default_role', $role);
        
        $group = new UserGroup();
        $group->setName('Administration');
        $group->setPermissions($this->getPermissions($group));
        $manager->persist($group);
        
        $this->setReference('default_group', $group);
        
        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setUsername('admin');
        $user->setEmail('admin@domain.org');
        $user->setEnabled(1);
        $user->setPassword('admin');
        $user->addRole($role);
        $user->getGroups()->add($group);
        $user->setApiKey($this->container->get('security.helper')->generateRandomPassword());
        $manager->persist($user);
        
        $manager->flush();
    }
    
    private function getPermissions(UserGroup $group)
    {
        $collection = new ArrayCollection();
        foreach ($this->container->get('router')->getRouteCollection()->all() as $name => $route) {
            if ($route->hasOption('require_admin_permission')) {
                $permission = new UserGroupPermission();
                $permission->setEnabled(true);
                $permission->setName($route->getOption('require_admin_permission'));
                $permission->setGroup($group);
                $collection->add($permission);
            }
        }
        
        return $collection;
    }
}
