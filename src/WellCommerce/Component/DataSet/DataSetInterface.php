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

namespace WellCommerce\Component\DataSet;

use WellCommerce\Component\DataSet\Cache\CacheOptions;
use WellCommerce\Component\DataSet\Column\ColumnCollection;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;
use WellCommerce\Component\DataSet\Transformer\ColumnTransformerCollection;

/**
 * Interface DataSetInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface DataSetInterface
{
    public function getIdentifier(): string;
    
    public function getColumns(): ColumnCollection;
    
    public function setColumns(ColumnCollection $columns);
    
    public function setColumnTransformers(ColumnTransformerCollection $transformers);
    
    public function getResult(string $contextType, array $requestOptions = [], array $contextOptions = []): array;
    
    public function configureOptions(DataSetConfiguratorInterface $configurator);
    
    public function setCacheOptions(CacheOptions $options);
}
