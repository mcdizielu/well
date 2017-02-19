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

namespace WellCommerce\Bundle\CoreBundle\DataSet;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\AbstractContainerAware;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Component\DataSet\Cache\CacheOptions;
use WellCommerce\Component\DataSet\Column\ColumnCollection;
use WellCommerce\Component\DataSet\Column\ColumnInterface;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;
use WellCommerce\Component\DataSet\DataSetInterface;
use WellCommerce\Component\DataSet\Manager\DataSetManagerInterface;
use WellCommerce\Component\DataSet\QueryBuilder\DataSetQueryBuilder;
use WellCommerce\Component\DataSet\Request\DataSetRequestInterface;
use WellCommerce\Component\DataSet\Transformer\ColumnTransformerCollection;

/**
 * Class AbstractDataSet
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractDataSet extends AbstractContainerAware implements DataSetInterface
{
    /**
     * @var ColumnCollection
     */
    protected $columns;
    
    /**
     * @var RepositoryInterface
     */
    protected $repository;
    
    /**
     * @var ColumnTransformerCollection
     */
    protected $columnTransformers;
    
    /**
     * @var DataSetManagerInterface
     */
    protected $manager;
    
    /**
     * @var CacheOptions
     */
    protected $cacheOptions;
    
    /**
     * AbstractDataSet constructor.
     *
     * @param RepositoryInterface     $repository
     * @param DataSetManagerInterface $manager
     */
    public function __construct(RepositoryInterface $repository, DataSetManagerInterface $manager)
    {
        $this->repository         = $repository;
        $this->manager            = $manager;
        $this->columns            = new ColumnCollection();
        $this->columnTransformers = new ColumnTransformerCollection();
        $this->cacheOptions       = new CacheOptions();
    }
    
    public function setCacheOptions(CacheOptions $options)
    {
        $this->cacheOptions = $options;
    }
    
    public function getColumns(): ColumnCollection
    {
        return $this->columns;
    }
    
    public function setColumns(ColumnCollection $columns)
    {
        $this->columns = $columns;
    }
    
    public function addColumn(ColumnInterface $column)
    {
        $this->columns->add($column);
    }
    
    public function setColumnTransformers(ColumnTransformerCollection $transformers)
    {
        $this->columnTransformers = $transformers;
    }
    
    abstract public function configureOptions(DataSetConfiguratorInterface $configurator);
    
    public function getResult(string $contextType, array $requestOptions = [], array $contextOptions = []): array
    {
        $this->getDoctrineHelper()->enableFilter('locale')->setParameter('locale', $this->getRequestHelper()->getCurrentLocale());
        
        $context      = $this->manager->createContext($contextType, $contextOptions, $this->columnTransformers);
        $request      = $this->manager->createRequest($requestOptions);
        $queryBuilder = $this->getQueryBuilder($request);
        
        try {
            $result = $context->getResult($queryBuilder, $request, $this->columns, $this->cacheOptions);
        } catch (\Exception $e) {
            $result = [
                'error' => $e->getMessage(),
            ];
        }
        
        $this->getDoctrineHelper()->disableFilter('locale');
        
        return $result;
    }
    
    abstract protected function createQueryBuilder(): QueryBuilder;
    
    protected function getQueryBuilder(DataSetRequestInterface $request): QueryBuilder
    {
        $dataSetQueryBuilder = new DataSetQueryBuilder($this->createQueryBuilder());
        
        return $dataSetQueryBuilder->getQueryBuilder($this->columns, $request);
    }
}
