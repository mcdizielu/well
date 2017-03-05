<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\ShowcaseBundle\Controller\Box;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\DataSet\Conditions\Condition\Eq;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class ShowcaseBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShowcaseBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $categories = [];
        $collection = $this->getCategories($boxSettings);
        $status     = $boxSettings->getParam('status');
        $collection->map(function (Category $category) use (&$categories, $status, $boxSettings) {
            $conditions = $this->createConditionsCollection($status, $category->getId());
            $limit      = $boxSettings->getParam('limit', 10);
            
            $dataset = $this->get('product.dataset.front')->getResult('array', [
                'limit'      => $limit,
                'order_by'   => 'hierarchy',
                'order_dir'  => 'asc',
                'conditions' => $conditions,
            ]);
            
            if ($dataset['pagination']['totalRows'] >= $limit) {
                $categories[] = [
                    'id'             => $category->getId(),
                    'name'           => $category->translate()->getName(),
                    'products_count' => $category->getProductsCount(),
                    'dataset'        => $dataset,
                ];
            }
        });
        
        return $this->displayTemplate('index', [
            'showcase'    => $categories,
            'boxSettings' => $boxSettings,
        ]);
    }
    
    private function createConditionsCollection(int $status, int $categoryId): ConditionsCollection
    {
        $conditions = new ConditionsCollection();
        $conditions->add(new Eq('status', $status));
        $conditions->add(new Eq('category', $categoryId));
        
        return $conditions;
    }
    
    private function getCategories(LayoutBoxSettingsCollection $boxSettings): Collection
    {
        $categories = $this->getCategoryIdentifiers($boxSettings);
        $criteria   = new Criteria();
        $criteria->where($criteria->expr()->isNull('parent'));
        $criteria->andWhere($criteria->expr()->eq('enabled', true));
        if (!empty($categories)) {
            $criteria->andWhere($criteria->expr()->in('id', $categories));
        }
        
        return $this->get('category.repository')->matching($criteria);
    }
    
    private function getCategoryIdentifiers(LayoutBoxSettingsCollection $boxSettings): array
    {
        $categories = $boxSettings->getParam('categories', []);
        
        return array_filter($categories, function ($k) {
            return is_int($k);
        }, ARRAY_FILTER_USE_KEY);
    }
}
