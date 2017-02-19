<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\OAuthBundle\Manager;

use League\OAuth2\Client\Provider\FacebookUser;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class FacebookClientManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FacebookClientManager extends AbstractManager
{
    public function getClient(FacebookUser $facebookUser): Client
    {
        $email = $facebookUser->getEmail();
        
        $client = $this->repository->findOneBy([
            'clientDetails.username' => $email,
        ]);
        
        if (!$client instanceof Client) {
            $client = $this->autoRegisterFacebookUser($facebookUser);
        }
        
        return $client;
    }
    
    private function autoRegisterFacebookUser(FacebookUser $facebookUser): Client
    {
        $firstName = $facebookUser->getFirstName();
        $lastName  = $facebookUser->getLastName();
        $email     = $facebookUser->getEmail();
        
        /** @var $client Client */
        $client = $this->initResource();
        $client->getClientDetails()->setUsername($email);
        $client->getClientDetails()->setPassword($this->getSecurityHelper()->generateRandomPassword());
        
        $client->getContactDetails()->setEmail($email);
        $client->getContactDetails()->setFirstName($firstName);
        $client->getContactDetails()->setLastName($lastName);
        $client->getContactDetails()->setPhone(' ');
        $client->getContactDetails()->setSecondaryPhone(' ');
        
        $this->createResource($client);
        
        return $client;
    }
}
