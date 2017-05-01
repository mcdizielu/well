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

namespace WellCommerce\Bundle\CatalogBundle\DataSet\Front;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CatalogBundle\Entity\Producer;
use WellCommerce\Bundle\CatalogBundle\Entity\ProducerTranslation;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Cache\CacheOptions;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class ProducerCollectionDataSet
 *
 * @author Rafał Martonik <rafal@wellcommerce.org>
 */
class ProducerCollectionDataSet extends AbstractDataSet
{
    public function getIdentifier(): string
    {
        return 'front.producer_collection';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'         => 'producer_collection.id',
            'name'       => 'producer_collection_translation.name',
            'route'      => 'IDENTITY(producer_collection_translation.route)',
            'shop'       => 'producer_collection_shops.id',
            'producerId' => 'producer_collection_producers.id',
            'photo'      => 'photos.path',
            'products'   => 'COUNT(producer_collection_products.id)',
        ]);
        
        $configurator->setColumnTransformers([
            'route' => $this->manager->createTransformer('route'),
        ]);
        
        $configurator->setCacheOptions(new CacheOptions(true, 3600, [
            Product::class,
            Producer::class,
            ProducerTranslation::class,
        ]));
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->groupBy('producer_collection.id');
        $queryBuilder->leftJoin('producer_collection.translations', 'producer_collection_translation');
        $queryBuilder->leftJoin('producer_collection.producer', 'producer_collection_producers');
        $queryBuilder->leftJoin('producer_collection.products', 'producer_collection_products');
        $queryBuilder->leftJoin('producer_collection.shops', 'producer_collection_shops');
        $queryBuilder->leftJoin('producer_collection.photo', 'photos');
        
        $queryBuilder->where($queryBuilder->expr()->eq('producer_collection_shops.id',
            $this->getShopStorage()->getCurrentShopIdentifier()));
        
        return $queryBuilder;
    }
}
