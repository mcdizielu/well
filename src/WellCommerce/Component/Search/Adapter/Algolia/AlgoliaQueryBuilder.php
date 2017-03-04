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

namespace WellCommerce\Component\Search\Adapter\Algolia;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Component\Search\Adapter\AbstractQueryBuilder;
use WellCommerce\Component\Search\Model\FieldInterface;

/**
 * Class AlgoliaQueryBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AlgoliaQueryBuilder extends AbstractQueryBuilder
{
    protected function createMultiFieldSearch(Collection $fields)
    {
        $attributesToRetrieve = [];
        
        $this->request->getType()->getFields()->map(function (FieldInterface $field) use (&$attributesToRetrieve) {
            $attributesToRetrieve[] = $field->getName();
        });
        
        return implode(',', $attributesToRetrieve);
    }
    
    protected function createSimpleSearch(string $phrase)
    {
        $attributesToRetrieve = [];
        
        return $attributesToRetrieve;
    }
}
