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
use WellCommerce\Bundle\CmsBundle\Entity\Contact;
use WellCommerce\Bundle\CmsBundle\Entity\ContactTranslation;
use WellCommerce\Bundle\CoreBundle\Doctrine\Fixtures\AbstractDataFixture;

/**
 * Class LoadContactData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadContactData extends AbstractDataFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->createDefaultContact($manager);
        
        $this->createLayoutBoxes($manager, [
            'contact' => [
                'type' => 'Contact',
                'name' => 'Contact us',
            ],
        ]);
        
        $manager->flush();
    }
    
    private function createDefaultContact(ObjectManager $manager)
    {
        $contact = new Contact();
        
        foreach ($this->getLocales() as $locale) {
            /** @var ContactTranslation $translation */
            $translation = $contact->translate($locale->getCode());
            $translation->setName('Sales department');
            $translation->setEmail('sales@domain.org');
            $translation->setBusinessHours($this->getBusinessHours());
            $translation->setPhone('555 123-345-678');
        }
        
        $contact->mergeNewTranslations();
        $manager->persist($contact);
    }
    
    private function getBusinessHours(): string
    {
        return implode('<br />', [
            'mon: 9am-5:30pm',
            'tue: 9am-5:30pm',
            'wed: 9am-5:30pm',
            'thu: 9am-5:30pm',
            'fri: 9am-5:30pm',
            'sat: 10am-2pm',
            'sun: not available',
        ]);
    }
}
