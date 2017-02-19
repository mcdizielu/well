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

namespace WellCommerce\Bundle\AppBundle\Manager;

use WellCommerce\Bundle\AppBundle\Entity\User;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class UserManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class UserManager extends AbstractManager
{
    public function resetPassword(string $username)
    {
        $user     = $this->getUser($username);
        $password = $this->getSecurityHelper()->generateRandomPassword();
        $user->setPassword($password);
        $this->updateResource($user);
        
        $this->getMailerHelper()->sendEmail([
            'recipient'     => $user->getEmail(),
            'subject'       => 'user.email.title.reset_password',
            'template'      => 'WellCommerceAppBundle:Admin/Email:reset_password.html.twig',
            'parameters'    => [
                'user'     => $user,
                'password' => $password,
            ],
            'configuration' => $this->getShopStorage()->getCurrentShop()->getMailerConfiguration(),
        ]);
    }
    
    private function getUser(string $username): User
    {
        return $this->repository->findOneBy(['username' => $username]);
    }
}
