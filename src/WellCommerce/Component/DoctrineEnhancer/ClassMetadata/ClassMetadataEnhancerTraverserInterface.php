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

namespace WellCommerce\Component\DoctrineEnhancer\ClassMetadata;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Interface ClassMetadataEnhancerTraverserInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ClassMetadataEnhancerTraverserInterface
{
    public function traverse(ClassMetadataInfo $metadata);
}
