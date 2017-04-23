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

use WellCommerce\Bundle\AppBundle\Entity\SystemConfiguration;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class SystemConfigurationManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class SystemConfigurationManager extends AbstractManager
{
    public function saveConfiguration(array $data)
    {
        foreach ($data as $paramName => $paramValue) {
            $configuration = $this->repository->findOneBy(['paramName' => $paramName]);
            if (!$configuration instanceof SystemConfiguration) {
                $configuration = $this->initResource();
                $this->getEntityManager()->persist($configuration);
            }
            
            $configuration->setParamValue($paramValue);
        }
        
        $this->getEntityManager()->flush();
    }
}
