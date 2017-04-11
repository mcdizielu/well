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

namespace WellCommerce\Bundle\CatalogBundle\DataSet\Admin;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;


/**
 * Class ProducerCollectionDataSet
 *
 * @author Rafał Martonik <rafal@wellcommerce.org>
 */
class ProducerCollectionDataSet extends AbstractDataSet
{
    /**
     * {@inheritdoc}
     */
    
    public function getIdentifier(): string
    {
        return 'admin.producer_collection';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'           => 'producer_collection.id',
            'name'         => 'producer_collection_translation.name',
            'producerName' => 'producer_translation.name',
        ]);
    }
    
    protected function createQueryBuilder() : QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->groupBy('producer_collection.id');
        $queryBuilder->leftJoin('producer_collection.translations', 'producer_collection_translation');
        $queryBuilder->leftJoin('producer_collection.producer', 'producer_info');
        $queryBuilder->leftJoin('producer_info.translations', 'producer_translation');
        $queryBuilder->leftJoin('producer_collection.shops', 'producer_collection_shops');
        $queryBuilder->where($queryBuilder->expr()->eq('producer_collection_shops.id',
            $this->getShopStorage()->getCurrentShopIdentifier()));
        
        return $queryBuilder;
    }
    
}
