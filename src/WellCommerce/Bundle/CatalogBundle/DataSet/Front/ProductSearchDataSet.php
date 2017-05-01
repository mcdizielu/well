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
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;
use WellCommerce\Component\Search\Storage\SearchResultStorage;

/**
 * Class ProductSearchDataSet
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductSearchDataSet extends ProductDataSet
{
    public function getIdentifier(): string
    {
        return 'front.product_search';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'                 => 'product.id',
            'sku'                => 'product.sku',
            'barcode'            => 'product.barcode',
            'enabled'            => 'product.enabled',
            'name'               => 'product_translation.name',
            'shortDescription'   => 'product_translation.shortDescription',
            'description'        => 'product_translation.description',
            'route'              => 'IDENTITY(product_translation.route)',
            'weight'             => 'product.weight',
            'netPrice'           => 'product.sellPrice.netAmount',
            'price'              => 'product.sellPrice.grossAmount',
            'discountedPrice'    => 'product.sellPrice.discountedGrossAmount',
            'discountedNetPrice' => 'product.sellPrice.discountedNetAmount',
            'isDiscountValid'    => 'IF_ELSE(:date BETWEEN IF_NULL(product.sellPrice.validFrom, :date) AND IF_NULL(product.sellPrice.validTo, :date) AND product.sellPrice.discountedGrossAmount > 0, 1, 0)',
            'finalPrice'         => 'IF_ELSE(:date BETWEEN IF_NULL(product.sellPrice.validFrom, :date) AND IF_NULL(product.sellPrice.validTo, :date) AND product.sellPrice.discountedGrossAmount > 0, product.sellPrice.discountedGrossAmount, product.sellPrice.grossAmount) * currency_rate.exchangeRate',
            'currency'           => 'product.sellPrice.currency',
            'tax'                => 'sell_tax.value',
            'stock'              => 'product.stock',
            'producerId'         => 'IDENTITY(product.producer)',
            'producerName'       => 'producers_translation.name',
            'category'           => 'categories.id',
            'filteredCategory'   => 'filtered_categories.id',
            'categoryName'       => 'categories_translation.name',
            'categoryRoute'      => 'IDENTITY(categories_translation.route)',
            'shop'               => 'product_shops.id',
            'photo'              => 'photos.path',
            'status'             => 'IDENTITY(distinction.status)',
            'variantOption'      => 'IDENTITY(variant_option.attributeValue)',
            'distinctions'       => 'product.id',
            'hierarchy'          => 'product.hierarchy',
            'isStatusValid'      => 'IF_ELSE(:date BETWEEN IF_NULL(distinction.validFrom, :date) AND IF_NULL(distinction.validTo, :date), 1, 0)',
            'score'              => 'FIELD(product.id, :identifiers)',
            'popularity'         => 'product.popularity',
        ]);
        
        $configurator->setColumnTransformers([
            'route' => $this->manager->createTransformer('route'),
        ]);
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $identifiers  = $this->getSearchResultStorage()->getResult();
        $queryBuilder = parent::createQueryBuilder();
        $expression   = $queryBuilder->expr()->in('product.id', ':identifiers');
        $queryBuilder->andWhere($expression);
        $queryBuilder->setParameter('identifiers', $identifiers);
        
        return $queryBuilder;
    }
    
    private function getSearchResultStorage(): SearchResultStorage
    {
        return $this->get('search.result.storage');
    }
}
