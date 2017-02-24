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

namespace WellCommerce\Component\DataSet\Configurator;

use WellCommerce\Component\DataSet\Cache\CacheOptions;
use WellCommerce\Component\DataSet\DataSetInterface;

/**
 * Class DataSetConfiguratorInterface
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
interface DataSetConfiguratorInterface
{
    public function configure(DataSetInterface $dataset);
    
    public function setColumns(array $columns = []);
    
    public function setColumnTransformers(array $transformers = []);
    
    public function setCacheOptions(CacheOptions $options);
}
