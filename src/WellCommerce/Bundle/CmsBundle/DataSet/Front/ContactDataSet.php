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

namespace WellCommerce\Bundle\CmsBundle\DataSet\Front;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CmsBundle\DataSet\Admin\ContactDataSet as BaseDataSet;

/**
 * Class ContactDataSet
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ContactDataSet extends BaseDataSet
{
    public function getIdentifier(): string
    {
        return 'front.contact';
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->groupBy('contact.id');
        $queryBuilder->leftJoin('contact.translations', 'contact_translation');
        $queryBuilder->leftJoin('contact.shops', 'contact_shops');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('contact_shops.id', $this->getShopStorage()->getCurrentShopIdentifier()));
        
        return $queryBuilder;
    }
}
