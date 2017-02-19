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

use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\ClientBillingAddress;
use WellCommerce\Bundle\AppBundle\Entity\ClientShippingAddress;
use WellCommerce\Bundle\CoreBundle\Doctrine\Fixtures\AbstractDataFixture;

/**
 * Class LoadClientData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadClientData extends AbstractDataFixture
{
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->createDemoClient($manager);
        
        $this->createLayoutBoxes($manager, [
            'client_registration'    => [
                'type' => 'ClientRegistration',
                'name' => 'Sign-in',
            ],
            'client_login'           => [
                'type' => 'ClientLogin',
                'name' => 'Sign-up',
            ],
            'client_order'           => [
                'type' => 'ClientOrder',
                'name' => 'Orders',
            ],
            'client_settings'        => [
                'type' => 'ClientSettings',
                'name' => 'Account settings',
            ],
            'client_menu'            => [
                'type' => 'ClientMenu',
                'name' => 'Client menu',
            ],
            'client_forgot_password' => [
                'type' => 'ClientForgotPassword',
                'name' => 'Password reset',
            ],
            'client_address_book'    => [
                'type' => 'ClientAddressBook',
                'name' => 'Address book',
            ],
        ]);
        
        $manager->flush();
    }
    
    private function createDemoClient(ObjectManager $manager)
    {
        $email          = 'demo@wellcommerce.org';
        $fakerGenerator = $this->getFakerGenerator();
        $firstName      = $fakerGenerator->firstName;
        $lastName       = $fakerGenerator->lastName;
        $clientGroup    = $this->getReference('client_group');
        
        $client = new Client();
        $client->getContactDetails()->setFirstName($firstName);
        $client->getContactDetails()->setLastName($lastName);
        $client->getContactDetails()->setEmail($email);
        $client->getContactDetails()->setPhone($fakerGenerator->phoneNumber);
        
        $client->getClientDetails()->setDiscount(25);
        $client->getClientDetails()->setUsername($email);
        $client->getClientDetails()->setHashedPassword('demo');
        $client->getClientDetails()->setConditionsAccepted(true);
        $client->getClientDetails()->setNewsletterAccepted(true);
        
        $client->setClientGroup($clientGroup);
        
        $billingAddress = new ClientBillingAddress();
        $billingAddress->setFirstName($firstName);
        $billingAddress->setLastName($lastName);
        $billingAddress->setLine1($fakerGenerator->address);
        $billingAddress->setLine2('');
        $billingAddress->setPostalCode($fakerGenerator->postcode);
        $billingAddress->setCity($fakerGenerator->city);
        $billingAddress->setCountry($fakerGenerator->countryCode);
        $billingAddress->setVatId($fakerGenerator->randomDigit);
        $billingAddress->setCompanyName($fakerGenerator->company);
        $billingAddress->setState('');
        
        $shippingAddress = new ClientShippingAddress();
        $shippingAddress->setFirstName($firstName);
        $shippingAddress->setLastName($lastName);
        $shippingAddress->setLine1($fakerGenerator->address);
        $shippingAddress->setLine2('');
        $shippingAddress->setPostalCode($fakerGenerator->postcode);
        $shippingAddress->setCity($fakerGenerator->city);
        $shippingAddress->setCountry($fakerGenerator->countryCode);
        $shippingAddress->setState('');
        $shippingAddress->setCopyBillingAddress(true);
        
        $client->setBillingAddress($billingAddress);
        $client->setShippingAddress($shippingAddress);
        
        $client->setShop($this->getReference('shop'));
        
        $manager->persist($client);
    }
}
