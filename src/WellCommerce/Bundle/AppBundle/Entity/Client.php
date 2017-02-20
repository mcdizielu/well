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
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class Client
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Client implements EntityInterface, \Serializable, UserInterface, EquatableInterface, EncoderAwareInterface
{
    const ROLE_CLIENT = 'ROLE_CLIENT';
    
    use Identifiable;
    use Timestampable;
    use Blameable;
    use ShopAwareTrait;
    
    /**
     * @var Collection
     */
    protected $orders;
    
    /**
     * @var ClientDetails
     */
    protected $clientDetails;
    
    /**
     * @var ClientContactDetails
     */
    protected $contactDetails;
    
    /**
     * @var ClientBillingAddress
     */
    protected $billingAddress;
    
    /**
     * @var ClientShippingAddress
     */
    protected $shippingAddress;
    
    /**
     * @var ClientGroup
     */
    protected $clientGroup;
    
    public function __construct()
    {
        $this->orders          = new ArrayCollection();
        $this->clientDetails   = new ClientDetails();
        $this->contactDetails  = new ClientContactDetails();
        $this->billingAddress  = new ClientBillingAddress();
        $this->shippingAddress = new ClientShippingAddress();
    }
    
    public function getPassword()
    {
        return $this->clientDetails->getPassword();
    }
    
    public function getSalt()
    {
        return null;
    }
    
    public function getUsername()
    {
        return $this->clientDetails->getUsername();
    }
    
    public function eraseCredentials()
    {
    }
    
    public function getRoles()
    {
        return [
            self::ROLE_CLIENT,
        ];
    }
    
    public function serialize()
    {
        return serialize([$this->id, $this->getUsername(), $this->getPassword()]);
    }
    
    public function unserialize($serialized)
    {
        list($this->id, $username, $password) = unserialize($serialized);
        if (!$this->clientDetails instanceof ClientDetails) {
            $this->clientDetails = new ClientDetails();
        }
        $this->clientDetails->setUsername($username);
        $this->clientDetails->setPassword($password);
    }
    
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof Client) {
            return false;
        }
        
        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }
        
        if ($this->getUsername() !== $user->getUsername()) {
            return false;
        }
        
        return true;
    }
    
    public function getOrders(): Collection
    {
        return $this->orders->filter(function (Order $order) {
            return $order->isConfirmed();
        });
    }
    
    public function getClientDetails(): ClientDetails
    {
        return $this->clientDetails;
    }
    
    public function setClientDetails(ClientDetails $clientDetails)
    {
        $this->clientDetails = $clientDetails;
    }
    
    public function getContactDetails(): ClientContactDetails
    {
        return $this->contactDetails;
    }
    
    public function setContactDetails(ClientContactDetails $contactDetails)
    {
        $this->contactDetails = $contactDetails;
    }
    
    public function getBillingAddress(): ClientBillingAddress
    {
        return $this->billingAddress;
    }
    
    public function setBillingAddress(ClientBillingAddress $billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }
    
    public function getShippingAddress(): ClientShippingAddress
    {
        return $this->shippingAddress;
    }
    
    public function setShippingAddress(ClientShippingAddress $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }
    
    public function getEncoderName()
    {
        return $this->clientDetails->getLegacyPasswordEncoder() ?? null;
    }
    
    public function getClientGroup()
    {
        return $this->clientGroup;
    }
    
    public function setClientGroup(ClientGroup $clientGroup = null)
    {
        $this->clientGroup = $clientGroup;
    }
}
