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

/**
 * Class ClientBillingAddress
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientBillingAddress
{
    use AddressTrait;
    
    protected $firstName   = '';
    protected $lastName    = '';
    protected $vatId       = '';
    protected $companyName = '';
    protected $viesValid   = false;
    
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }
    
    public function getLastName(): string
    {
        return $this->lastName;
    }
    
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }
    
    public function getVatId(): string
    {
        return $this->vatId;
    }
    
    public function setVatId(string $vatId)
    {
        $this->vatId = $vatId;
    }
    
    public function getCompanyName(): string
    {
        return $this->companyName;
    }
    
    public function setCompanyName(string $companyName)
    {
        $this->companyName = $companyName;
    }
    
    public function isViesValid(): bool
    {
        return $this->viesValid;
    }
    
    public function setViesValid(bool $viesValid)
    {
        $this->viesValid = $viesValid;
    }
}
