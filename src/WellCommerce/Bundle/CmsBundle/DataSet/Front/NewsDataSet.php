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
use WellCommerce\Bundle\CmsBundle\DataSet\Admin\NewsDataSet as BaseDataSet;
use WellCommerce\Bundle\CmsBundle\Entity\News;
use WellCommerce\Bundle\CmsBundle\Entity\NewsTranslation;
use WellCommerce\Component\DataSet\Cache\CacheOptions;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class NewsDataSet
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class NewsDataSet extends BaseDataSet
{
    public function getIdentifier(): string
    {
        return 'front.news';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        parent::configureOptions($configurator);
        
        $configurator->setColumnTransformers([
            'route' => $this->manager->createTransformer('route')
        ]);
        
        $configurator->setCacheOptions(new CacheOptions(true, 3600, [
            News::class,
            NewsTranslation::class,
        ]));
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->leftJoin('news.translations', 'news_translation');
        $queryBuilder->leftJoin('news.photo', 'photos');
        $queryBuilder->leftJoin('news.shops', 'news_shops');
        $queryBuilder->leftJoin('news.category', 'news_category');
        $queryBuilder->leftJoin('news_category.translations', 'news_category_translation');
        $queryBuilder->groupBy('news.id');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('news.publish', true));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('news_shops.id', $this->getShopStorage()->getCurrentShopIdentifier()));
        
        return $queryBuilder;
    }
}
