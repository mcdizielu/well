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

namespace WellCommerce\Bundle\CmsBundle\Request;

use WellCommerce\Bundle\CmsBundle\Entity\Contact;

/**
 * Class ContactRequestStorage
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ContactRequestStorage
{
    /**
     * @var Contact
     */
    protected $currentContact;
    
    public function setCurrentContact(Contact $contact)
    {
        $this->currentContact = $contact;
    }
    
    public function getCurrentContact(): Contact
    {
        return $this->currentContact;
    }
    
    public function hasCurrentContact(): bool
    {
        return $this->currentContact instanceof Contact;
    }
}
