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

namespace WellCommerce\Bundle\CmsBundle\DataSet\Admin;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class NewsCategoryDataSet
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class NewsCategoryDataSet extends AbstractDataSet
{
    public function getIdentifier(): string
    {
        return 'admin.news_category';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'   => 'news_category.id',
            'name' => 'news_category_translation.name',
        ]);
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->leftJoin('news_category.translations', 'news_category_translation');
        $queryBuilder->groupBy('news_category.id');
        
        return $queryBuilder;
    }
}
