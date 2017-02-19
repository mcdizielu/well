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

namespace WellCommerce\Bundle\CatalogBundle\Repository;

use WellCommerce\Bundle\CatalogBundle\Entity\Attribute;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeGroup;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;

/**
 * Interface AttributeRepositoryInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface AttributeRepositoryInterface extends RepositoryInterface
{
    public function getAttributeSet(AttributeGroup $attributeGroup): array;
    
    public function getAttributeValuesSet(Attribute $attribute): array;
}
