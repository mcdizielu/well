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
    public function getConfiguration(string $paramName)
    {
        return $this->getRepository()->findOneBy(['paramName' => $paramName]);
    }
    
    public function updateParameter(string $paramName, string $paramValue)
    {
        $configuration = $this->getConfiguration($paramName);
        if (!$configuration instanceof SystemConfiguration) {
            /** @var SystemConfiguration $configuration */
            $configuration = $this->initResource();
            $configuration->setParamName($paramName);
            $this->getEntityManager()->persist($configuration);
        }
        
        $configuration->setParamValue($paramValue);
        
        $this->getEntityManager()->flush();
    }
}
