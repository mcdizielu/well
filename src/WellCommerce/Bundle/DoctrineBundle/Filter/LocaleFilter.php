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

namespace WellCommerce\Bundle\DoctrineBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use WellCommerce\Bundle\DoctrineBundle\Entity\LocaleAwareInterface;

/**
 * Class LocaleFilter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LocaleFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$targetEntity->reflClass->implementsInterface(LocaleAwareInterface::class)) {
            return "";
        }
        
        return $targetTableAlias . '.locale = ' . $this->getParameter('locale');
    }
}
