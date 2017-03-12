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

namespace WellCommerce\Bundle\FeatureBundle\DataSet\Admin;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class FeatureGroupDataSet
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureGroupDataSet extends AbstractDataSet
{
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'   => 'feature_group.id',
            'name' => 'feature_group_translation.name',
            'sets' => 'GROUP_CONCAT(DISTINCT feature_set_translation.name ORDER BY feature_set_translation.name ASC SEPARATOR \', \')',
        ]);
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->groupBy('feature_group.id');
        $queryBuilder->leftJoin('feature_group.translations', 'feature_group_translation');
        $queryBuilder->leftJoin('feature_group.sets', 'feature_sets');
        $queryBuilder->leftJoin('feature_sets.translations', 'feature_set_translation');
        
        return $queryBuilder;
    }
    
    
    public function getIdentifier(): string
    {
        return 'admin.feature_group';
    }
}
