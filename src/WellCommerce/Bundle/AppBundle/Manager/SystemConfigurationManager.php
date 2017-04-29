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
    public function getConfiguration(string $name)
    {
        return $this->getRepository()->findOneBy(['name' => $name]);
    }
    
    public function saveParameters(string $name, array $parameters)
    {
        $configuration = $this->getConfiguration($name);
        if (!$configuration instanceof SystemConfiguration) {
            /** @var SystemConfiguration $configuration */
            $configuration = $this->initResource();
            $configuration->setName($name);
            $this->getEntityManager()->persist($configuration);
        }

        $configuration->setParameters($parameters);

        $this->getEntityManager()->flush();
    }
}
