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
use WellCommerce\Bundle\AppBundle\Entity\Company;
use WellCommerce\Bundle\AppBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadCompanyData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadCompanyData extends AbstractDataFixture
{
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $fakerGenerator = $this->getFakerGenerator();
        $company        = new Company();
        $company->setName($fakerGenerator->company . ' ' . $fakerGenerator->companySuffix);
        $company->setShortName($fakerGenerator->company);
        $company->setLine1($fakerGenerator->address);
        $company->setLine2('');
        $company->setPostalCode($fakerGenerator->postcode);
        $company->setCity($fakerGenerator->city);
        $company->setCountry($fakerGenerator->countryCode);
        $company->setState('');
        $manager->persist($company);
        $manager->flush();
        
        $this->setReference('company', $company);
    }
}
